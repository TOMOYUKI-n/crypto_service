@extends('layouts.app')

@section('content')


<div class="l-main__container">
    <div class="p-pass__container">

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="p-login__inner" style="display: block;">
                <label for="email" class="p-pass__title">{{ __('E-Mail Address') }}</label>
                <div>
                    <input id="email" type="email" class="p-pass__form @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="c-alert__caution2">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="p-login__inner" style="display: block;">
                <label for="password" class="p-pass__title">{{ __('Password') }}</label>
                <div>
                    <input id="password" type="password" class="p-pass__form @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    @error('password')
                        <span class="c-alert__caution2">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="p-login__inner" style="display: block;">
                <label for="password-confirm" class="p-pass__title">{{ __('Confirm Password') }}</label>
                <div>
                    <input id="password-confirm" type="password" class="p-pass__form" name="password_confirmation" required autocomplete="new-password">
                </div>
            </div>
            <div class="p-top__login">
                    <button type="submit" class="p-login__text p-btn__login">
                        {{ __('Reset Password') }}
                    </button>
            </div>
        </form>
    </div>
</div>
@endsection
