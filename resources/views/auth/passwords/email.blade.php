@extends('layouts.app')

@section('content')


<div class="l-main__container">
    <div class="p-pass__container">
        <!-- タイトル -->
        <div class="c-main__title">
            {{ __('Reset Password') }}
        </div>
        <!-- 内容 -->
        <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="p-login__inner">
                        <label for="email" class="p-pass__title">{{ __('E-Mail Address') }}</label>
                        <div>
                            <input id="email" type="email" class="p-pass__form @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="p-login__inner">
                        <div class="p-pass__btn">
                            <button type="submit" class="p-login__text p-btn__login">
                                {{ __('Send Password Reset Link') }}
                            </button>
                        </div>
                    </div>
                </form>
        </div>
    </div>
</div>
@endsection
