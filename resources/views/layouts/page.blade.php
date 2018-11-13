@extends('layouts.base')
@section('main')
<main class="container my-4 py-4 bg-white shadow-sm rounded">
    <h1>@yield('title')</h1>
    @yield('content')
</main>
@endsection
