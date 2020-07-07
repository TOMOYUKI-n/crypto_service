@extends('layouts.app')

@section('content')
<div class="l-main__container">
    <div class="p-verify__container">
        <!-- タイトル -->
        <div class="c-main__title">
            {{ __('Verify Your Email Address') }}
        </div>

        <!-- 内容 -->
        <div class="p-login__inner">
            @if (session('resent'))
            <div class="alert alert-success" role="alert">
                {{ __('A fresh verification link has been sent to your email address.') }}
            </div>
            @endif

            <div class="p-verify__text">
                <p>
                    {{ __('Before proceeding, please check your email for a verification link.') }}
                </p>
            </div>
            <div class="p-verify__here">
                <a href="{{ route('verification.resend') }}" class="p-login__alarttext">
                    {{ __('If you did not receive the email click here to request another') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection