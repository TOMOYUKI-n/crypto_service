@extends('layouts.app')

@section('content')
<div class="l-main__container">
        <div class="p-login__container">

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="p-login__inner">
                        <input id="email" type="email" class="p-login__form @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="メールアドレス" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="p-login__inner">
                        <input id="password" type="password" class="p-login__form @error('password') is-invalid @enderror" name="password" placeholder="パスワード"　required autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="p-login__input">
                            <div class="p-login__check">
                                <input class="p-login__form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="p-login__form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                    </div>
                    <!--通常のログイン-->
                    <div class="p-top__login">
                        <button type="submit" class="p-login__text p-btn__login">
                            {{ __('Login') }}
                        </button>
                        @if (Route::has('password.request'))
                            <a class="p-login__forgot p-login__alarttext p-login-add-margin__1" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                    <div class="p-login-add-margin__3">
                        <p class="p-login__text">アカウントをお持ちで無い方はこちらから</p>
                        <div class="p-top__register">
                            <div class="p-login__text p-btn__new">
                                <a href="{{ route('register') }}">{{ __('Register') }}</a>
                            </div>
                        </div>
                    </div>
                    
                </form>

        </div>
</div>
@endsection
