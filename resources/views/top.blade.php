
@extends('layouts.app')

@section('title','Home')

@section('content')


<div class="l-main-top-barner">
    <div class="p-top__top">
        <div class="p-top__head">
            <div class="p-top__top-message">
                <p class="p-top__introduction">Crypto Trend</p>
                <p class="p-top__introduction">仮想通貨のトレンドを、これひとつで。</p>
            </div>
        </div>

        <!--ログインエリア-->
        <div class="p-top__account">
            <div class="p-top__register">
                <a class="p-top__text p-btn__new p-btn__margin"
                    href="{{ route('register') }}">
                        {{ __('Register Free') }}
                </a>
                <a class="p-top__text p-btn__tw-new"
                    href="{{ url('login/twitter') }}">
                    {{ __('Twitter Login') }}
                </a>
            </div>
            <div class="p-top__login">
                <p class="p-top__text">既に会員の方はこちら</p>
                <a class="p-top__text p-btn__login"
                    href="{{ route('login') }}">
                        {{ __('Login') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!--特徴紹介エリア-->
<div class="p-top__section-container">
    <div class="p-top__section-title">Crypto Trendの特徴</div>
    <div class="p-top__section-wrap">
        
        <div class="p-top__section1">
            <img class="p-top__img__trend" src="{{ asset('/images/twitter-crop.jpg') }}" alt="trend">
            <div class="p-top__section__subtitle">Twitterの仮想通貨のトレンドがわかる</div>
            <p class="p-top__section__text">
            もうtwitterにかじりつくことはありません。
            これひとつでTwitter上に出回る仮想通貨の情報を収集し、
            トレンドを把握できます
            </p>
        </div>
        <div class="p-top__section2">
            <img class="p-top__img__follow" src="{{ asset('/images/smartphone.jpg') }}" alt="follow">
            <div class="p-top__section__subtitle">アカウントを自動フォロー</div>
            <p class="p-top__section__text">
                仮想通貨のユーザーをフォローしきれない。
                自動で仮想通貨に関するアカウントをフォローします。
                あなたの時間はより分析へ充てれます。
            </p>
        </div>
        <div class="p-top__section3">
            <img class="p-top__img__news" src="{{ asset('/images/newsletter-crop.png') }}" alt="news">
            <div class="p-top__section__subtitle">最新の情報をお届けします</div>
            <p class="p-top__section__text">
                仮想通貨に関連する記事やニュースが、あなたの手の中で。
            </p>
        </div>
    </div>
</div>

@endsection