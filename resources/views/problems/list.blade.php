@extends('layouts.page')
@section('title', __('name.problem.list'))
@section('content')
<a href="{{route('random_problem')}}" class="btn btn-info">{{__('ui.problem.random')}}</a>
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
            <th scope="col">@sortablelink('id', 'ID')</th>
            <th scope="col">{{__('ui.problem.title')}}</th>
            <th scope="col">@sortablelink('creator',__('ui.problem.creator'))</th>
            <th scope="col">@sortablelink('difficulty',__('ui.problem.difficulty'))</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($problems as $problem)
            <tr class="{{$problem->solved_by(auth()->user())?'table-success':''}}">
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
<p>
    {{$problems->appends(\Request::except('page'))->render()}}
</p>
@endsection

@section('style')
<style>.progress{min-width:60px;}</style>
@endsection
