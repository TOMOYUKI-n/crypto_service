@extends('layouts.app')

@section('content')

<div class="l-main__container">
    <div class="p-login__container">

        <div class="c-main__title p-regist__potision-center">
            {{ __('New Register') }}
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="p-regist__form">
                <div class="p-regist__input">
                    <input id="name" type="text" placeholder="ニックネーム" class="p-login__form @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="p-regist__form">
                <div class="p-regist__input">
                    <input id="email" type="email" placeholder="メールアドレス" class="p-login__form @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="p-regist__form">
                <div class="p-regist__input">
                    <input id="password" type="password" placeholder="パスワード"　class="p-login__form @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="p-regist__form">
                <div class="p-regist__input">
                    <input id="password-confirm" type="password" placeholder="確認用パスワード" class="p-login__form" name="password_confirmation" required autocomplete="new-password">
                </div>
            </div>
            <div class="p-regist__btn-primary">
                <button type="submit" class="p-login__text p-btn__new">
                    {{ __('Register') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
