@extends('layouts.page')
@section('title', __('name.submissions.'.($me?'me':'all')))
@section('content')
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
            <td><a href="{{route('submission',['id'=>$submission->id])}}">{{__('ui.submission.detail')}}</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
