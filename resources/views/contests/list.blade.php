@extends('layouts.page')
@section('title', __('name.contest.list'))
@section('content')

@if (auth()->check() && auth()->user()->has_permission('create_contest'))
<p><a href="{{route('create_contest')}}" class="btn btn-info">{{__('ui.contest.create_new')}}</a></p>
@endif
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
            <th scope="col">@sortablelink('id', 'ID')</th>
            <th scope="col">{{__('ui.contest.title')}}</th>
            <th scope="col">@sortablelink('creator',__('ui.contest.creator'))</th>
            <th scope="col">@sortablelink('start_time',__('ui.contest.open'))</th>
            <th scope="col">@sortablelink('end_time',__('ui.contest.close'))</th>
            <th scope="col">{{__('ui.status')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contests as $contest)
            <tr href="{{route('contest',['id'=>$contest->id])}}" class="{{
					(strtotime(date("Y-m-d H:i:s")) < strtotime($contest->start_time)) ? 'table-success' :
                    ((strtotime(date("Y-m-d H:i:s")) < strtotime($contest->end_time)) ? 'table-primary' :
                    'table-secondary')}}">
                <th scope="row">{{$contest->{'id'} }}</th>
                <td><a href="{{route('contest',['id'=>$contest->id])}}">{{$contest->{'title'} }}</a></td>
                <td>{{$contest->creator}}</td>
                <td>{{$contest->start_time}}</td>
                <td>{{$contest->end_time}}</td>
                <td>{{(strtotime(date("Y-m-d H:i:s")) < strtotime($contest->start_time)) ? __('ui.contest.not_started_short') :
                                 ((strtotime(date("Y-m-d H:i:s")) < strtotime($contest->end_time)) ? __('ui.contest.in_progress_short') :
                                 __('ui.contest.ended_short'))}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<p>
    {{$contests->appends(\Request::except('page'))->render('vendor.pagination.bootstrap-4-binary')}}
</p>
@endsection

@section('style')
<style>
.progress{min-width:60px;}
tbody>tr{cursor: pointer;}
</style>
@endsection

@section('script')
<script>$(function(){$('tbody>tr').flexible_link();});</script>
@endsection
