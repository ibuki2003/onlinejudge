<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
use Kunststube\Rison;
use Symfony\Component\HttpKernel\Exception\HttpException;


class StatisticsController extends Controller
{
    public function aggregateApi(Request $request){
        /*
        for    table to aggregate
        each   aggregate column
        count  count value
        filter filter rows with Rison           (optional)
        order  sort order                       (optional)
        map    return values as map             (true if exists)
        uniq   distinct count value             (true if exists)
        limit  max row count                    (true if exists)
        remain show remain params(with limit)   (true if exists)
        */
        $request->validate([
            'for'   => 'string|required',
            'each'  => 'string|required',
            'count' => 'string|required',
            'filter'=> 'string|nullable',
            'order' => [Rule::in(['asc', 'desc']),'nullable'],
            'limit' => 'integer|nullable',
        ]);
        if($request->for=='users'){
            throw new HttpException(403, 'Not permitted');
        }
        if($request->has('filter')){
            try {
                $decoder = new Rison\RisonDecoder($request->filter);
                $filter = $decoder->decode();
            } catch (Rison\RisonParseErrorException $e) {
                throw new HttpException(400, 'Invalid Rison:'.$e->getMessage());
            }
        }else{
            $filter=[];
        }

        $query=DB::table($request->for);

        $query->select(
            $request->each,
            DB::raw('count(' . ($request->has('uniq')?'distinct ':'') . $query->getGrammar()->wrap($request->count) . ') as count')
        );
        
        
        foreach ($filter as $key => $value) {
            $query->where($key, $value);
        }
        
        $query->groupBy($request->each);
        if($request->has('order')){
            $query->orderBy('count', $request->order);
        }

        if($request->has('limit'))
            $query->limit($request->limit);

        try{
            $data=$query->get();
        }catch(QueryException $e){
            throw new HttpException(400, 'Error');
        }

        if($request->has('limit') && $request->has('remain')){
            $query=DB::table($request->for);
            foreach ($filter as $key => $value) {
                $query->where($key, $value);
            }
            $remain_cnt=$query->count() - $data->sum('count');
            if($remain_cnt>0){
                $data->push([
                    $request->each => 'Other',
                    'count'        => $remain_cnt
                ]);
            }
        }
        
        if($request->has('map')){
            return $data->pluck('count', $request->each);
        }else{
            return $data;
        }
    }
}
