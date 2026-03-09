<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'БУ72' }}</title>
    @vite(['resources/css/app.css'])
    @livewireStyles
</head>
<body
    class="grid min-h-screen grid-rows-[auto_1fr_auto] bg-gradient-to-b from-blue-50 to-slate-50 font-sans text-gray-800">
<header class="sticky top-0 z-40 border-b border-gray-300 bg-white">
    <div class="container mx-auto ">
        <div class="
        flex flex-wrap md:flex-nowrap
        items-center justify-between
        gap-3 md:gap-3
        px-4 py-3 md:px-6 md:py-[18px]
    ">
            <div class="text-[34px] leading-none font-extrabold tracking-[2px] md:text-[42px]"><a href="/">БУ72</a>
            </div>

            <a href="{{ route('advertisements.create', ['catalog_id' => request()->routeIs('catalog.show') ? request()->route('id') : null]) }}"
               class="w-full rounded-lg bg-blue-700 px-4 py-2.5 text-center text-sm font-bold text-white no-underline md:w-auto">
                Добавить объявление
            </a>

            <div class="flex items-center gap-3.5">
                @auth
                    <span
                        class="text-sm text-gray-500">Логин: {{ auth()->user()->name ?: auth()->user()->email }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="cursor-pointer rounded-lg border border-blue-700 bg-transparent px-3.5 py-2 font-semibold text-blue-700">
                            Выход
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="rounded-lg border border-blue-700 bg-transparent px-3.5 py-2 font-semibold text-blue-700 no-underline">Вход</a>
                @endauth
            </div>
        </div>
    </div>

    <div class="border-t border-gray-200 px-4 pt-2.5 pb-3 md:px-6">
        <div class="container mx-auto ">
            <x-top-catalog-menu/>
            {{--        <x-second-level-catalog-menu />--}}
        </div>
    </div>
</header>


<main class="container mx-auto px-4 py-4 md:px-6 md:py-[26px]">

    <livewire:catalog.breadcrumbs/>

    @if(session('status'))
        <div
            class="mb-3.5 rounded-[10px] border border-green-300 bg-green-50 px-3 py-2.5 text-green-800">{{ session('status') }}</div>
    @endif

    @yield('content')

</main>

<footer class="flex flex-wrap flex-col sm:flex-col
    justify-between gap-4
    border-t border-gray-300
    bg-white
    px-4 py-3 md:px-6 md:py-3.5
    text-sm text-gray-500
    ">
    <span>{{ date('Y') }}. Все права защищены.</span>
    <span>Создание сайта <a href="https://php-cat.com" target="_blank" rel="noopener"
                            class="text-gray-500 no-underline hover:underline">php-cat.com</a></span>
</footer>

@livewireScripts
</body>
</html>
