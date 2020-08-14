@extends('layouts.app')

@section('content')
<div class="l-main__container">
  <div class="p-login__container">

    <div class="p-top__login">

        <p class="p-login__text">
          <h1>{{ __('送信完了') }}</h1>
        </p>
        <div class="p-top__login">
            <a class="p-top__text p-btn__login" href="{{ route('top') }}">
              トップページへ戻る
            </a>
        </div>

    </div>
  </div>
</div>
@endsection
