@extends('layouts.page')
@section('title', __('name.submit'))
@section('content')
<form action="{{route('submit')}}" method="post">
    @csrf
    <div class="form-group">
        <label for="problem-select">{{__('ui.submission.problemId')}}</label>
        <select id="problem-select" name="problem" class="form-control{{ $errors->has('problem') ? ' is-invalid' : '' }}">
            @foreach ($problems as $problem)
                <option {{$problem->id == $id?'selected ':''}}value="{{$problem->id}}">{{$problem->id}} {{$problem->title}}</option>
            @endforeach
        </select>
        @if ($errors->has('problem'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('problem') }}</strong>
        </div>
        @endif
    </div>
    <div class="form-group">
        <label for="lang-select">{{__('ui.submission.lang')}}</label>
        <select id="lang-select" name="lang" class="form-control{{ $errors->has('lang') ? ' is-invalid' : '' }}">
            @foreach ($langs as $lang)
                <option value="{{$lang->id}}">{{$lang->name}}</option>
            @endforeach
        </select>
        @if ($errors->has('lang'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('lang') }}</strong>
        </div>
        @endif
    </div>
    <div class="form-group">
        <label for="source-input">{{__('ui.submission.source')}}</label>
        <textarea id="source-input" name="source" class="form-control{{ $errors->has('source') ? ' is-invalid' : ''}}" rows="20"></textarea>
        @if ($errors->has('source'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('source') }}</strong>
        </div>
        @endif
    </div>
    <button type="submit" class="btn btn-primary">{{__('ui.submit')}}</button>
</form>
@endsection

