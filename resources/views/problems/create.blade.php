@extends('layouts.page')
@section('title', __('name.problem.create'))
@section('content')
<form action="{{route('create_problem')}}" method="post" name="form" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="title-input">{{__('ui.problem.title')}}</label>
        <input type="text" id="title-input" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title') }}" required>
        @if ($errors->has('title'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('title') }}</strong>
        </div>
        @endif
    </div>
    <div class="form-group">
        <label for="difficulty-input">{{__('ui.problem.difficulty')}}</label>
        <input type="number" id="difficulty-input" name="difficulty" class="form-control{{ $errors->has('difficulty') ? ' is-invalid' : '' }}" min="1" max="{{config('oj.difficulty_max')}}" value="{{ old('difficulty') }}" required>
        @if ($errors->has('difficulty'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('difficulty') }}</strong>
        </div>
        @endif
    </div>
    <div class="form-group">
        <label for="content-file">{{__('ui.zip_file')}}</label>
        <input type="file" id="content-file" name="zip_content" class="form-control-file{{ $errors->has('zip_content') ? ' is-invalid' : ''}}" required>
        @if ($errors->has('zip_content'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('zip_content') }}</strong>
        </div>
        @endif
    </div>
    <div class="form-group">
        <label for="open-time">{{__('ui.problem.open')}}</label>
        <input type="datetime-local" id="open-time" name="open" class="form-control{{ $errors->has('open') ? ' is-invalid' : ''}}" value="{{ old('open') }}">
        <div class="text-info">{{__('ui.problem.open_empty')}}</div>
        @if ($errors->has('open'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('open') }}</strong>
        </div>
        @endif
    </div>
    <button type="submit" class="btn btn-primary">{{__('ui.submit')}}</button>
</form>
<nav class="nav flex-column my-4">
    <a href="{{route('md_editor')}}">{{__('name.md_editor')}}</a>
</nav>
@endsection
