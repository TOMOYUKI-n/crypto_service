@extends('layouts.app')

@section('content')

    <div class="l-main-container">
        <div class="c-main__title">
            <span class="c-main__title__text">{{ __('Google News Index') }}</span>
            <span class="c-main__title__text">{{ __('About Crypto') }}</span>
        </div>
    </div>
  
    <div id="app">
            <news-component
            :newsline="{{ $newsline }}"
            ></news-component>
    </div>
    
@endsection