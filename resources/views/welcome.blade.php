<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head class="scroll-smooth">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Бу72 проект</title>

    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}


    <link href="{{ asset('css/app.css') }}?s=2208120328{{ rand() }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('storage/css/ionicons.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('storage/css/style.css') }}?s=2208120328" />
    {{-- <link rel="icon" type="image/png" href="/storage/site/img/logo47.png" /> --}}

    {{-- @vite('resources/css/app.css') --}}

    {{-- <meta property="og:title" content="Авто-АС запчасти"> --}}
    {{-- <meta property="og:site_name" content="avto-as.ru"> --}}
    {{-- <meta property="og:url" content="https://avto-as.ru"> --}}
    {{-- <meta property="og:description" content="Запчасти для автомобилей"> --}}
    {{-- <meta property="og:image" content="https://22.avto-as.ru/storage/site/img/logo2112.png"> --}}
    {{-- <meta property="og:image:width" content="968"> --}}
    {{-- <meta property="og:image:height" content="504"> --}}




</head>

<body>
    <div id="app"></div>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function(m, e, t, r, i, k, a) {
            m[i] = m[i] || function() {
                (m[i].a = m[i].a || []).push(arguments)
            };
            m[i].l = 1 * new Date();
            k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(
                k, a)
        })(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
        ym(36225915, "init", {
            clickmap: true,
            trackLinks: true,
            accurateTrackBounce: true
        });
    </script> <noscript>
        <div><img src="https://mc.yandex.ru/watch/36225915" style="position:absolute; left:-9999px;" alt="" />
        </div>
    </noscript> <!-- /Yandex.Metrika counter -->
</body>
<script src="{{ asset('js/app.js') }}?s=2208130539{{ rand() }}"></script>

</html>
