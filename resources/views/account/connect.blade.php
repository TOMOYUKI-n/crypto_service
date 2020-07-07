@extends('layouts.app')

@section('content')

    <div class="l-main-container">
        <div class="c-main__title">{{ __('Twitterアカウント登録') }}</div>
        
        <div class="c-main__function-head">
            <div class="p-account__text p-account__intro">当該アカウントとあなたのtwitterアカウントと連携します。
            </div>
            <div class="p-account__text p-account__intro">
                ※こちらのサービスから関連するTwitterユーザーをフォローすることができます
            </div>
        </div>

    <!--登録前であればこちらを表示する-->
    <!--登録フォーム表示エリア-->
        <form method="POST" action="{{ route('account.link') }}">
            @csrf

            <div class="p-btn__follow p-btn__API-account-conect p-account__section-bottom">
                ここにAPIを埋め込む
            </div>
        </form>
    <!--登録フォーム表示エリア-->

    <!--・登録後のリダイレクト、
        ・twitterでのログイン済み
        ・本機能での登録済み　　　　であればこちらを表示する-->
    <!--アカウント表示エリア-->
        <div class="p-account__connected">{{ __('登録済みです') }}</div>
        
        <div class="p-account__panel-wrap">
            <div class="p-account__inner">
                <div class="p-account__section-top">
                    <div class="p-account__inner2">
                        <div class="p-account__name">
                            松本ひとし
                        </div>    
                        <div class="p-account__username">
                            @oike221kjik42lk1
                        </div>
                    </div>
                    <div class="p-account__inner3">
                        <div class="p-account__title">フォロー</div>
                        <div class="p-account__follow">{{ __('$78') }}</div>
                    </div>
                    <div class="p-account__inner4">
                        <div class="p-account__title">フォロワー</div>
                        <div class="p-account__follow">29000</div>
                    </div>
                </div>
                <div class="p-account__section-middle">
                    <div class="p-account__prof">
                        ここにプロフィールが表示されます。最大１６０文字を表示する。
                        ここにプロフィールが表示されます。最大１６０文字を表示する。
                        ここにプロフィールが表示されます。最大１６０文字を表示する。
                        ここにプロフィールが表示されます。最大１６０文字を表示する。
                        ここにプロフィールが表示されます。最大１６０文字を表示する。
                        ここにプロフィール！
                    </div>
                </div>
            </div>
        </div>
    <!--アカウント表示エリア-->

    </div>
@endsection