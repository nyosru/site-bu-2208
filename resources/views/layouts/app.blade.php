<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'БУ72' }}</title>
    @vite(['resources/css/app.css'])
    @livewireStyles
</head>
<body class="site-body">
<header class="site-header">
    <div class="site-header-main">
        <div class="brand">БУ72</div>

        <div class="auth-area">
            @auth
                <span class="user-login">Логин: {{ auth()->user()->name ?: auth()->user()->email }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="auth-link">Выход</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="auth-link">Вход</a>
            @endauth
        </div>
    </div>

    <div class="site-header-catalogs">
        <x-top-catalog-menu />
        <x-second-level-catalog-menu />
    </div>
</header>

<main class="site-main">
    @yield('content')
</main>

<footer class="site-footer">
    <span>{{ date('Y') }}. Все права защищены.</span>
    <span>Создание сайта <a href="https://php-cat.com" target="_blank" rel="noopener">php-cat.com</a></span>
</footer>

@livewireScripts
</body>
</html>
