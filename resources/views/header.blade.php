@section('header')

    <header class="p-navbar__main-wrap">

        <div class="logo">
            <h1 class="logo__pad">
                <a href="/">Crypto Trend</a>
            </h1>
        </div>

        <!--PC-->
        <nav>
            @guest
            <ul class="p-navbar__section">
                <li class="p-navbar__list p-navbar__text"><a href="/login"><p>ログイン</p></a></li>
                @if (Route::has('register'))
                    <li class="p-navbar__list p-navbar__text"><a href="/register"><p>新規登録</p></a></li>
                @endif
            </ul>
            @else
            <ul class="p-navbar__section ">
                <li class="p-navbar__list p-navbar__text"><a href="/trend"><p>トレンド一覧</p></a></li>
                <li class="p-navbar__list p-navbar__text"><a href="/account"><p>アカウント一覧</p></a></li>
                <li class="p-navbar__list p-navbar__text"><a href="/news"><p>NEWS一覧</p></a></li>
                <li class="p-navbar__list p-navbar__text"><p>{{ $user->name }}</p></a></li>
                <li class="p-navbar__list p-navbar__text">
                    <a href="{{ route('logout') }}"
                        onclick ="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        <p>ログアウト</p>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="p-header__sp__form">
                        @csrf
                    </form>
                </li>
            </ul>
            @endguest
        </nav>
        <div class="sp__icon__container js_push">
            <i class="fas fa-bars p-navbar__icon "></i>
        </div>

        <!--SP-->
        <div class="p-header__sp p-header__sp--right js_toggle">

            <div class="p-header__sp__icon__container js_push">
                <i class="fas fa-times p-header__sp__icon"></i>
            </div>

            @guest
            <ul class="p-header__sp__menu">
                <li class="p-header__sp__menu__list"><a href="/login"><p>ログイン</p></a></li>
                @if (Route::has('register'))
                    <li class="p-header__sp__menu__list"><a href="/register"><p>新規登録</p></a></li>
                @endif
            </ul>
            @else
            <ul class="p-header__sp__menu">
                <li class="p-navbar__list p-navbar__text"><p>{{ $user->name }}</p></a></li>
                <li class="p-header__sp__menu__list "><a href="/trend"><p>トレンド一覧</p></a></li>
                <li class="p-header__sp__menu__list "><a href="/account"><p>アカウント一覧</p></a></li>
                <li class="p-header__sp__menu__list "><a href="/news"><p>NEWS一覧</p></a></li>
                <li class="p-header__sp__menu__list ">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        <p>ログアウト</p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="p-header__sp__form">
                        @csrf
                    </form>
                </li>
            </ul>
            @endguest
        </div>
    </header>
@show