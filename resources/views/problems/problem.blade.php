@extends('layouts.page')
@section('title', __('#'.$problem->id.' '.$problem->title))
@section('content')
<h2>{{__('ui.problem.creator')}}:{{$problem->creator}}</h2>
@if ($problem->has_editorial())
<p><a href="{{route('problem_editorial',['id'=>$problem->id])}}" class="btn btn-secondary">{{__('name.editorial')}}</a></p>
@endif
@if ($problem->creator === auth()->id())
<p><a href="{{route('problem_edit',['id'=>$problem->id])}}" class="btn btn-dark">{{__('name.problem.edit')}}</a></p>
@endif
@if (!$problem->is_opened())
<div class="alert alert-info" role="alert">{{__('ui.problem.not_opened')}}</div>
@endif
<hr>
<div id="md">{{$problem->get_content()}}</div>

<a href="{{route('submit',['id'=>$problem->id])}}" class="btn btn-primary">{{__('name.submit')}}</a>
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
