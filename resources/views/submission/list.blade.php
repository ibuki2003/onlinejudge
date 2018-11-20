@extends('layouts.page')
@section('title', __('name.submissions.'.($me?'me':'all')))
@section('content')
<div class="form-inline my-4">
    <div class="form-group m-2">
        <label for="filter_problem">問題</label>
        <input type="number" class="form-control" id="filter_problem">
    </div>
    <div class="form-group m-2">
        <label for="filter_lang">言語</label>
        <select class="form-control" id="filter_lang">
                <option value="">-</option>
                @foreach($langs as $lang)
                    <option value="{{$lang->id}}">{{$lang->name}}</option>
                @endforeach
        </select>
    </div>
    <div class="form-group m-2">
        <label for="filter_status">結果</label>
        <select class="form-control" id="filter_status">
                <option value="">-</option>
                @foreach(['AC','WA','CE','TLE','OLE','IE','RE'] as $stat)
                    <option class="text-{{config('oj.status_color')[$stat]}}" value="{{$stat}}">{{$stat}}</option>
                @endforeach
        </select>
    </div>
    <div class="form-group m-2">
        <label for="filter_sender">ユーザ</label>
        <input type="text" class="form-control" id="filter_sender">
    </div>
</div>
<div class="table-responsive">
    <output id="last"></output><span id="stat"></span>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">{{__('ui.submission.problemId')}}</th>
                <th scope="col">{{__('ui.submission.sender')}}</th>
                <th scope="col">{{__('ui.submission.lang')}}</th>
                <th scope="col">{{__('ui.submission.point')}}</th>
                <th scope="col">{{__('ui.submission.size')}}</th>
                <th scope="col">{{__('ui.submission.time')}}</th>
                <th scope="col">{{__('ui.submission.status')}}</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <button class="btn" id="prev">{{__('pagination.previous')}}</button>
    <button class="btn" id="next">{{__('pagination.next')}}</button>
</div>
@endsection

@section('style')
<link rel="stylesheet" href="{{asset('css/loadicon.css')}}">
@endsection

@section('script')
<script src="{{asset('js/api_table.js')}}"></script>
<script>
    var statusColors={
        @foreach (config('oj.status_color') as $key=>$data)
        {{$key}}:'table-{{$data}}',
        @endforeach
    };
    $(function(){
        autoreload('/api/submissions{{$me?'/me':''}}', $('tbody'), $('#prev'), $('#next'), $('#stat'), $('#last'), [
            'id',
            function(data){return '<a href="/problems/'+data.problem+'">'+data.problem+'</a>'}, // problem
            'sender',
            'lang',
            'point',
            'size',
            function(data){return data.time.date;}, // time
            'status',
            function(data){return '<a href="/submissions/'+data.id+'">詳細</a>';} // detail
        ], 5000,
        function(){
            ret={};
            if($('#filter_problem').val()!='')ret['problem']=$('#filter_problem').val();
            if($('#filter_lang')   .val()!='')ret['lang']   =$('#filter_lang').val();
            if($('#filter_status') .val()!='')ret['status'] =$('#filter_status').val();
            if($('#filter_sender') .val()!='')ret['sender'] =$('#filter_sender').val();
            return ret;
        },
        function(row, data){
            row.addClass(statusColors[data['status']]);
        });
    });
</script>
@endsection
