@extends('layouts.app')

@section('content')
<div class="l-main__container">
    <div id="login" class="p-login__container">

        <form method="POST" action="{{ route('contact.confirm') }}">
            @csrf
            <div class="p-login__inner">
                <input id="email" type="email" class="p-login__form @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="メールアドレス" required>
                @error('email')
                    <span class="p-login__inputError">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="p-login__inner">
                <input id="title" type="text" class="p-login__form @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" placeholder="タイトル"　required>
                @error('title')
                    <span class="p-login__inputError">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="p-contact__wrap">
                <div class="p-contact__title">お問い合わせ内容</div>
                <textarea name="body" class="p-login__form" style="min-height: 130px;">{{ old('body') }}</textarea>
                @if ($errors->has('body'))
                    <p class="p-login__inputError">{{ $errors->first('body') }}</p>
                @endif
            </div>
            <!--通常のログイン-->

            <div class="p-top__login">
                <button type="submit" class="p-login__text p-btn__login">
                    入力内容確認
                </button>
            </div>
            
        </form>
    </div>
</div>
@endsection
