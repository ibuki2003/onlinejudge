@extends('layouts.page')
@section('title', config('app.name', 'Laravel'))
@section('content')
<ul class="nav flex-column">
    @if (auth()->user()->has_permission('submit'))
        <li class="nav-item">
            <a class="nav-link" href="{{route('problems')}}">{{__('name.problem.list')}}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('submissions_me')}}">{{__('name.submissions.me')}}</a>
        </li>
    @endif
    @if (auth()->user()->has_permission('create_problem'))
        <li class="nav-item">
            <a class="nav-link" href="{{route('create_problem')}}">{{__('name.problem.create')}}</a>
        </li>
    @endif
    @if (auth()->user()->has_permission('admit_users'))
        <li class="nav-item">
            <a class="nav-link" href="{{route('submissions')}}">{{__('name.submissions.all')}}</a>
        </li>
    @endif
    <li class="nav-item">
        <a class="nav-link" href="{{route('statistics')}}">{{__('name.statistics')}}</a>
    </li>
</ul>

@endsection
