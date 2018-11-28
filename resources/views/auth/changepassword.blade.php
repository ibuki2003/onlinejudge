@extends('layouts.page')
@section('title', __('name.change_password'))
@section('content')
@if(session('success'))
    <p class="alert alert-success">{{session('success')}}</p>
@endif

<form method="POST" action="{{ route('change_password') }}">
    @csrf

    <div class="form-group row">
        <label for="old-password" class="col-md-4 col-form-label text-md-right">{{__('ui.old_password')}}</label>

        <div class="col-md-6">
            <input id="old-password" type="password" class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}" name="old_password" value="" required autofocus>

            @if ($errors->has('old_password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('old_password') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <label for="new-password" class="col-md-4 col-form-label text-md-right">{{ __('ui.new_password') }}</label>

        <div class="col-md-6">
            <input id="new-password" type="password" class="form-control{{ $errors->has('new_password') ? ' is-invalid' : '' }}" name="new_password" required>

            @if ($errors->has('new_password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('new_password') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <label for="new-password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('ui.confirm') }}</label>

        <div class="col-md-6">
            <input id="new-password-confirm" type="password" class="form-control" name="new_password_confirmation" required>
        </div>
    </div>

    <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-primary">
                {{__('ui.submit')}}
            </button>
        </div>
    </div>
</form>
@endsection
