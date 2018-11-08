@extends('layouts.page')
@section('title', __('name.problemList'))
@section('content')
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
            <th scope="col">ID</th>
            <th scope="col">{{__('ui.problem.title')}}</th>
            <th scope="col">{{__('ui.problem.creator')}}</th>
            <th scope="col">{{__('ui.problem.difficulty')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($problems as $problem)
            <tr>
                <th scope="row">{{$problem->{'id'} }}</th>
                <td><a href="{{route('problem',['id'=>$problem->id])}}">{{$problem->{'title'} }}</a></td>
                <td>{{$problem->{'creator'} }}</td>
                <td>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: {{$problem->difficulty*100/config('oj.difficulty_max')}}%" aria-valuenow="{{$problem->difficulty}}" aria-valuemin="0" aria-valuemax="{{config('oj.difficulty_max')}}">{{$problem->difficulty}}</div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('style')
<style>.progress{min-width:60px;}</style>
@endsection
