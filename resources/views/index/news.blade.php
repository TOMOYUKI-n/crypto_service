@extends('layouts.app')

@section('content')

    <div class="l-main-container">
        <div class="c-main__title">
            <span class="c-main__title__text">仮想通貨関連</span>
            <span class="c-main__title__text">ニュース一覧</span>
        </div>
    </div>
  
    <div id="app">
            <news-component
            :newsline="{{ $newsline }}"
            ></news-component>
    </div>
    
@endsection