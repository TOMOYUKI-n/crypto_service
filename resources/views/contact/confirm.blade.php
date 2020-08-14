@extends('layouts.app')

@section('content')
<div class="l-main__container">
    <div id="login" class="p-login__container">

        <form method="POST" action="{{ route('contact.send') }}">
            @csrf
            <div class="p-login__inner">
                <div>メールアドレス</div>
                {{ $inputs['email'] }}
                <input type="hidden" class="p-login__form" name="email" value="{{ $inputs['email'] }}">
            </div>
            <div class="p-login__inner">
                <div>タイトル</div>
                {{ $inputs['title'] }}
                <input type="hidden" class="p-login__form" name="title" value="{{ $inputs['title'] }}">
            </div>

            <div class="p-login__inner">
                <div>お問い合わせ内容</div>
                {!! nl2br(e($inputs['body'])) !!}
                <input type="hidden" class="p-login__form" name="body" value="{{ $inputs['body'] }}">
            </div>

            <div class="p-top__login">
                <button type="submit" name="action" value="back" class="p-login__text p-btn__login">
                    入力内容修正
                </button>
            </div>
            <div class="p-top__login">
                <button type="submit"  name="action" value="submit"  class="p-login__text p-btn__login">
                    送信する
                </button>
            </div>
            
        </form>
    </div>
</div>
@endsection
