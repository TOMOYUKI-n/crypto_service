@extends('layouts.app')

@section('content')
<div class="l-main__container">
        <div class="p-login__container">

          <div class="p-top__login">
              <p class="p-login__text c-alert__caution1">注意</p>
              <p class="p-login__text c-alert__caution2">
                フォロー機能をお使いになる場合は、<br>twitterアカウントにメールアドレスの登録が必要です。
              </p>

              <button class="p-login__text p-btn__login">
                <a href="https://twitter.com/home" target=”_blank”>Twitterへ<i class="fas fa-external-link-alt"></i></a>
              </button>

              <p class="p-login__text c-alert__caution3">
                今すぐログインする場合はこちら
              </p>
              <button class="p-login__text p-btn__newregister">
                <a href="{{ route('register') }}">{{ __('make new account') }}</a>
              </button>
          </div>
</div>
@endsection
