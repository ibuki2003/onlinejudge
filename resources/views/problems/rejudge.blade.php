@extends('layouts.page')
@section('title', __('ui.problem.rejudge'))
@section('content')
<form action="{{route('problem_rejudge', ['id' => $problem->id])}}" method="post" name="form" enctype="multipart/form-data">
    @csrf
	<p>本当にこの問題への全ての提出をリジャッジしますか？</p>
    <button type="submit" class="btn btn-primary">{{__('ui.submit')}}</button>
</form>
@endsection
