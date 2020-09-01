<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="仮想通貨のトレンドが一眼でわかります。twitterの大量の情報を集計し、投資に役立てることができます。">
    <meta name="keywords" content="仮想通貨,twitter,トレンド">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Crypto Trend') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha256-UzFD2WYH2U1dQpKDjjZK72VtPeWP50NoJjd26rnAdUI=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>


</head>
<body>

    <div id="app"  class="wrapper--main">
        <main class="l-base">
            @include('header')
            
                <!-- フラッシュメッセージ -->
                    @if (session('flash_message'))
                    <div class="flash_message flash_message__fade">
                        {{ session('flash_message') }}
                    </div>
                    @endif
                    
                    @if (session('error_message'))
                    <div class="error_message">
                        {{ session('error_message') }}
                    </div>
                    @endif

            @yield('content')
        </main>
        <footer-component></footer-component>
    </div>

</body>
</html>
