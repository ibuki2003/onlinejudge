@extends('layouts.page')
@section('title', __('name.submission')." #$id")
@section('content')
<table class="table table-striped">
    <tbody>
        <tr>
            <th scope="row">{{__("ui.submission.time")}}</th>
            <td>{{$submission->time}}</td>
        </tr>
        <tr>
            <th scope="row">{{__("ui.submission.problemId")}}</th>
            <td><a href="{{route('problem',['id'=>$submission->problem])}}">{{$submission->problem}} {{$problem}}</a></td>
        </tr>
        <tr>
            <th scope="row">{{__("ui.submission.sender")}}</th>
            <td>{{$submission->sender}}</td>
        </tr>
        <tr>
            <th scope="row">{{__("ui.submission.lang")}}</th>
            <td>{{$lang->name}}</td>
        </tr>
        <tr>
            <th scope="row">{{__("ui.submission.point")}}</th>
            <td>{{$submission->point}}</td>
        </tr>
        <tr>
            <th scope="row">{{__("ui.submission.size")}}</th>
            <td>{{$submission->size}}</td>
        </tr>
        <tr class="table-{{config('oj.status_color')[$submission->status]}}">
            <th scope="row">{{__("ui.submission.status")}}</th>
            <td>{{$submission->status}}</td>
        </tr>
    </tbody>
</table>
<hr>
<h2>{{__('ui.submission.source')}}</h2>
<pre><code>{{$source}}</code></pre>

@if($compile_result!==NULL)
<h2>{{__('ui.submission.compile_result')}}</h2>
<pre><code>{{$compile_result}}</code></pre>
@endif

@if($judge_result!==NULL)
<h2>{{__('ui.problem.test_case.testcases')}}</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>{{__('ui.problem.test_case.set_name')}}</th>
            <th>{{__('ui.problem.test_case.point')}}</th>
            <th>{{__('ui.problem.test_case.testcases')}}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($judge_result->tcsets as $tcset)
        <tr><td>{{$tcset->name}}</td><td>{{$tcset->got ? $tcset->perfect : 0}}/{{$tcset->perfect}}</td><td>{{implode(',',$tcset->problems)}}</td></tr>
        @endforeach
    </tbody>
</table>

<table class="table table-striped">
    <thead>
        <tr>
            <th>{{__('ui.problem.test_case.filename')}}</th>
            <th>{{__('ui.problem.test_case.status')}}</th>
            <th>{{__('ui.problem.test_case.exec_time')}}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($judge_result->result as $testcase)
            <tr class="table-{{config('oj.status_color')[$testcase->status]}}">
                <th scope="row">{{$testcase->name}}</th>
                @if(in_array($testcase->status,['AC','WA']))
                    <td>{{$testcase->status}}</td><td>{{$testcase->time}}ms</td>
                @else
                    <td colspan=2>{{$testcase->status}}</td></tr>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
@endif

@endsection
