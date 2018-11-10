@extends('layouts.page')
@section('title', config('app.name', 'Laravel'))
@section('content')
<ul class="nav flex-column">
    @if (auth()->user()->has_permission('submit'))
        <li class="nav-item">
            <a class="nav-link" href="{{route('problems')}}">{{__('name.problemList')}}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('submissions_me')}}">{{__('name.submissions.me')}}</a>
        </li>
    @endif
    @if (auth()->user()->has_permission('admit_users'))
        <li class="nav-item">
            <a class="nav-link" href="{{route('submissions')}}">{{__('name.submissions.all')}}</a>
        </li>
    @endif
</ul>

@endsection
