<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use App\Models\Lang;
use App\Models\Problem;


class Submission extends Model
{
    protected $fillable = ['problem_id', 'lang_id', 'user_id', 'point', 'size', 'status', 'exec_time'];
    protected $dates = ['time'];
    protected $dateFormat='Y-m-d H:i:s';
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $perPage = 30;

    /**
     * @inheritdoc
     */
    public static function create(array $data) {
        $source=$data['source'];
        unset($data['source']);
        $data['size']=strlen($source);
        $data['user_id']=auth()->id();


        $model = static::query()->create($data);
        $id=$model->id;
        Storage::disk('data')->makeDirectory('submissions/'.$id);
        Storage::disk('data')->put('submissions/'.$id.'/source.'.$model->lang->extension, $source);

        $model->update(['status'=>'WJ']);

        return $model;
    }

    /**
     * filters only visible problem
     */
    public function scopeOwnFilter($query){
        return $query->Where('user_id', auth()->id());
    }

    /**
     * filters only visible problem
     */
    public function scopeFilterWithRequest($query){
        $request=request();
        if($request->filled('problem_id'))
            $query->Where('problem_id', $request->input('problem_id'));

        if($request->filled('lang_id'))
        $query->Where('lang_id', $request->input('lang_id'));

        if($request->filled('status'))
        $query->Where('status', $request->input('status'));

        if($request->filled('user_id'))
        $query->Where('user_id', $request->input('user_id'));

        return $query;
    }

    /**
     * returns whether the problem visible for the user.
     * @return bool
     */
    public function is_visible(){
        if(auth()->user()->has_permission('admit_users'))return true;
        return $this->user_id===auth()->id();
    }

    /**
     * returns the submission source
     * @return string
     */
    public function get_source(){
        return Storage::disk('data')->get('submissions/'.$this->id.'/source.'.$this->lang->extension);
    }

    /**
     * returns whether the submission has compile result
     * @return bool
     */
    public function has_compile_result(){
        return Storage::disk('data')->exists('submissions/'.$this->id.'/judge_log.txt');
    }

    /**
     * returns compile result
     * @return string
     */
    public function get_compile_result(){
        return Storage::disk('data')->get('submissions/'.$this->id.'/judge_log.txt');
    }

    /**
     * returns whether the submission has judge result
     * @return bool
     */
    public function has_judge_result(){
        return Storage::disk('data')->exists('submissions/'.$this->id.'/judge_log.json');
    }

    /**
     * returns judge result as json
     * @return object
     */
    public function get_raw_judge_result(){
        return Storage::disk('data')->get('submissions/'.$this->id.'/judge_log.json');
    }

    /**
     * returns judge result as object
     * @return object
     */
    public function get_judge_result(){
        return json_decode(get_raw_judge_result());
    }

    /**
     * rejudge the submission
     */
    public function rejudge(){
        $this->update([
            'status' => 'WR',
            'point' => 0,
            'exec_time' => null
        ]);
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function problem(){
        return $this->belongsTo('App\Models\Problem');
    }

    public function lang(){
        return $this->belongsTo('App\Models\Lang');
    }

}
