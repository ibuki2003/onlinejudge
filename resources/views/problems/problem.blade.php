@extends('layouts.page')
@section('title', __("#$id ".$problem->title))
@section('content')
<h2>{{__('ui.problem.creator')}}:{{$problem->creator}}</h2>
@if ($problem->open!==NULL)
<div class="alert alert-info" role="alert">{{__('ui.problem.not_opened')}}</div>
@endif
<hr>
<div id="md">{{$content}}</div>
@endsection

@section('style')
<link rel="stylesheet" href="{{asset('katex/katex.min.css')}}">
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/marked/0.5.1/marked.min.js"></script>
<script src="{{asset('katex/katex.min.js')}}"></script>
<script src="{{asset('katex/contrib/auto-render.min.js')}}"></script>
<script src="{{asset('js/mdparse.js')}}"></script>
<script>
$(function(){
    var elm=$('#md');
    renderMD(elm.text(),elm);
}); 
</script>
@endsection
