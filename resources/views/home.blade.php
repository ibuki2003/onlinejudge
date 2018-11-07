@extends('layouts.page')
@section('title', config('app.name', 'Laravel'))
@section('content')
<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link" href="{{route('problemList')}}">{{__('name.problemList')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{route('allSubmissions')}}">{{__('name.submissions.all')}}</a>
    </li>
</ul>

@endsection
