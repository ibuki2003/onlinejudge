@extends('layouts.page')
@section('title', __('name.submissions'))
@section('content')
<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">{{__('ui.submissionList.problemId')}}</th>
            <th scope="col">{{__('ui.submissionList.sender')}}</th>
            <th scope="col">{{__('ui.submissionList.lang')}}</th>
            <th scope="col">{{__('ui.submissionList.point')}}</th>
            <th scope="col">{{__('ui.submissionList.size')}}</th>
            <th scope="col">{{__('ui.submissionList.time')}}</th>
            <th scope="col">{{__('ui.submissionList.status')}}</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($submissions as $submission)
        <tr class="table-{{config('oj.status_color')[$submission->status]}}">
            <th scope="row">{{$submission->id}}</th>
            <td><a href="{{route('problem',['id'=>$submission->problem])}}">{{$submission->problem}}</a></td>
            <td>{{$submission->sender}}</td>
            <td>{{$langs[$submission->lang]}}</td>
            <td>{{$submission->point}}</td>
            <td>{{$submission->size}}</td>
            <td>{{$submission->time}}</td>
            <td>{{$submission->status}}</td>
            <td><a href="{{route('top')}}">{{__('ui.submissionList.detail')}}</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
