@extends('layouts.page')
@section('title', __('name.statistics'))
@section('content')
<h2>{{__('ui.problem.problem')}}</h2>
<div class="row d-flex flex-wrap">
    <div class="border rounded shadow graph" id="problem_creator"></div>
    <div class="border rounded shadow graph" id="problem_difficulty"></div>
</div>

<h2>{{__('ui.submission.submission')}}</h2>
<div class="row d-flex flex-wrap">
    <div class="border rounded shadow graph" id="submission_status"></div>
    <div class="border rounded shadow graph" id="submission_lang"></div>
    <div class="border rounded shadow graph" id="submission_user"></div>
</div>
@endsection

@section('style')
<style>
.graph{
    max-width: 100%;
    width: 640px;
    height: 480px;
    margin: .75em;
}
</style>

@section('script')
<script src="https://www.gstatic.com/charts/loader.js"></script>
<script src="{{asset('/js/chart.js')}}"></script>
@endsection
