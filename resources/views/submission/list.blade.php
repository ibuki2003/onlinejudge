@extends('layouts.page')
@section('title', __('name.submissions.'.($me?'me':'all')))
@section('content')
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
            'time',
            'status',
            function(data){return '<a href="/submissions/'+data.id+'">詳細</a>';} // detail
        ], 5000,
        function(row, data){
            row.addClass(statusColors[data['status']]);
        });
    });
</script>
@endsection
