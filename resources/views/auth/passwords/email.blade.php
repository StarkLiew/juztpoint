@extends('platform::auth')
@section('title',__('Reset Password'))

@section('content')
    <h1 class="h5 text-black">{{ __('Reset Password') }}</h1>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <form class="m-t-md"
          role="form"
          method="POST"
          data-controller="layouts--form"
          data-action="layouts--form#submit"
          data-layouts--form-button-animate="#button-email"
          data-layouts--form-button-text="{{ __('Loading...') }}"
          action="{{ route('platform.password.email') }}">
        @csrf
        <div class="form-group">
            <label>{{ __('E-Mail Address') }}</label>
            <div class="controls">
                <input type="email"
                       name="email"
                       placeholder="{{ __('Enter your email') }}"
                       class="form-control @error('password') is-invalid @enderror"
                       required
                       value="{{ old('email') }}">
                @error('email')
                    <span class="invalid-feedback text-danger">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-default btn-block" id="button-email" type="submit">
                <i class="icon-envelope text-xs m-r-xs"></i>  {{ __('Send Password Reset Link') }}
            </button>
        </div>
    </form>
@endsection