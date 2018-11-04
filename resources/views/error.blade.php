@extends('layouts.page')
@section('title', __($statuscode.' '.$name))
@section('content')
<p>{{__('httperror.'.$statuscode)}}</p>
@if (!empty($message))
    <p class="alert alert-danger">{{__($message)}}</p>
@endif
<p>
    <a href="{{route('top')}}" class="btn btn-primary">トップへ</a>
    <button type="button"  onclick="window.history.back()" class="btn btn-secondary">前のページへ</button>
</p>

@endsection
