@extends('layouts.page')
@section('title', __('name.submit'))
@section('content')
<form action="{{route('submit')}}" method="post" name="form">
    @csrf
    <div class="form-group">
        <label for="problem-select">{{__('ui.submission.problemId')}}</label>
        <select id="problem-select" name="problem_id" class="form-control{{ $errors->has('problem_id') ? ' is-invalid' : '' }}">
            @foreach ($problems as $problem)
                <option {{$problem->id == $id?'selected ':''}}value="{{$problem->id}}">{{$problem->id}} {{$problem->title}}</option>
            @endforeach
        </select>
        @if ($errors->has('problem_id'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('problem') }}</strong>
        </div>
        @endif
    </div>
    <div class="form-group">
        <label for="lang-select">{{__('ui.submission.lang')}}</label>
        <select id="lang-select" name="lang_id" class="form-control{{ $errors->has('lang_id') ? ' is-invalid' : '' }}">
            @foreach ($langs as $lang)
                <option value="{{$lang->id}}">{{$lang->name}}</option>
            @endforeach
        </select>
        @if ($errors->has('lang_id'))
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
    <button type="button" onclick="submit_source()" class="btn btn-primary">{{__('ui.submit')}}</button>
</form>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script>
    function submit_source(){
        $.cookie('lang',$('#lang-select option:selected').val(),{expires:365});
        form.submit();
    }
    $(function(){
        if($.cookie('lang')){
            $('#lang-select').val($.cookie('lang'));
        }
    });
</script>
@endsection

