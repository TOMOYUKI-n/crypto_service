@extends('layouts.app')

@section('content')


<div class="l-main__container">
    <div >
        <!-- タイトル -->
            <div class="c-main__title">
            {{ __('Reset Password') }}
            </div>
            <div class="p-pass__container">
                @if (session('status'))
                    <div class="c-alert__success">
                        {{ session('status') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="p-login__inner" style="display: block;">
                        <label for="email" class="p-pass__title">{{ __('E-Mail Address') }}</label>
                        <div>
                            <input id="email" type="email" class="p-pass__form @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="error_message">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="p-top__login">
                            <button type="submit" class="p-login__text p-btn__login">
                                {{ __('Send Password Reset Link') }}
                            </button>
                    </div>
                </form>
            </div>
    </div>
</div>
@endsection
