@extends('layouts.page')
@section('title', config('app.name', 'Laravel'))
@section('content')
<ul class="nav flex-column">
    @if (config('oj.open_mode') || auth()->check() && auth()->user()->has_permission('submit'))
        <li class="nav-item">
            <a class="nav-link" href="{{route('problems')}}">
                <i class="fas fa-fw fa-server"></i>
                {{__('name.problem.list')}}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('contests')}}">
                <i class="fas fa-calendar-check"></i>
                {{__('name.contest.list')}}
            </a>
        </li>
    @endif
    @if (auth()->check() && auth()->user()->has_permission('submit'))
        <li class="nav-item">
            <a class="nav-link" href="{{route('submissions_me')}}">
                <i class="fas fa-fw fa-file"></i>
                {{__('name.submissions.me')}}
            </a>
        </li>
    @endif
    @if (auth()->check() && auth()->user()->has_permission('create_problem'))
        <li class="nav-item">
            <a class="nav-link" href="{{route('create_problem')}}">
                <i class="fas fa-fw fa-upload"></i>
                {{__('name.problem.create')}}
            </a>
        </li>
    @endif
    @if (config('oj.open_mode') || auth()->check() && auth()->user()->has_permission('admit_users'))
        <li class="nav-item">
            <a class="nav-link" href="{{route('submissions')}}">
                <i class="fas fa-fw fa-file-alt"></i>
                {{__('name.submissions.all')}}
            </a>
        </li>
    @endif
    @if (auth()->check() && auth()->user()->has_permission('admit_users'))
            <li class="nav-item">
            <a class="nav-link" href="{{route('manage_users')}}">
                <i class="fas fa-fw fa-user-cog"></i>
                {{__('name.manage_users')}}
            </a>
        </li>
    @endif
    <li class="nav-item">
        <a class="nav-link" href="{{route('statistics')}}">
            <i class="fas fa-fw fa-info"></i>
            {{__('name.statistics')}}
        </a>
    </li>
    @if (config('oj.help_url'))
        <li class="nav-item">
            <a class="nav-link" href="{{config('oj.help_url')}}">
                <i class="fas fa-fw fa-question-circle"></i>
                {{__('name.help')}}
            </a>
        </li>
    @endif
</ul>

@endsection
