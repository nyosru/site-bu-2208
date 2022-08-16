<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head class="scroll-smooth" >

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
    {{-- <div id="vk_community_messages" style="position: fixed; padding-bottom: 120px; width: 50px; height: 50px; z-index: 10000; 
        bottom: 0px; right: 20px; margin: 0px 0px 20px; background: rgba(0, 0, 0, 0) none repeat scroll 0% 0%;"> --}}
    {{-- <iframe name="fXD74537" allow="autoplay" src="https://vk.com/reforged_widget.php?app=0&amp;width=300px&amp;_ver=1&amp;gid=25381804&amp;disable_welcome_screen=1&amp;ref_source_info=undefined&amp;ref_source_link=https%3A%2F%2Favto-as.ru%2Fshow%2F&amp;tooltip_text=%D0%95%D1%81%D1%82%D1%8C%20%D0%B2%D0%BE%D0%BF%D1%80%D0%BE%D1%81%3F&amp;domain=avto-as.ru&amp;expand_timeout=500000&amp;button_position=undefined&amp;height=399&amp;url=https%3A%2F%2Favto-as.ru%2Fshow%2F&amp;referrer=&amp;title=%D0%90%D0%B2%D1%82%D0%BE-%D0%90%D0%A1%20%D0%B7%D0%B0%D0%BF%D1%87%D0%B0%D1%81%D1%82%D0%B8%2C%20%D1%80%D0%B0%D1%81%D1%85%D0%BE%D0%B4%D0%BD%D0%B8%D0%BA%D0%B8%2C%20%D0%BB%D0%B0%D0%BC%D0%BF%D1%8B%20%D0%B8%20%D0%BC%D0%B0%D1%81%D0%BB%D0%B0%20%D0%B2%20%D0%A2%D1%8E%D0%BC%D0%B5%D0%BD%D0%B8&amp;1825ff484d3" 
        scrolling="no" id="vkwidget1" style="overflow: hidden; box-shadow: none;" width="50" height="50" frameborder="0"></iframe> --}}
    {{-- </div> --}}

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function(m, e, t, r, i, k, a) {
            m[i] = m[i] || function() {
                (m[i].a = m[i].a || []).push(arguments)
            };
            m[i].l = 1 * new Date();
            k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(
                k, a)
        })
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(89950536, "init", {
            clickmap: true,
            trackLinks: true,
            accurateTrackBounce: true
        });
    </script>
    <noscript>
        <div><img src="https://mc.yandex.ru/watch/89950536" style="position:absolute; left:-9999px;" alt="" />
        </div>
    </noscript>
    <!-- /Yandex.Metrika counter -->

</body>

{{-- <script src="{{ asset('js/app.js') }}?s=2208120337{{ rand() }}"></script> --}}
<script src="{{ asset('js/app.js') }}?s=2208130539{{ rand() }}"></script>

{{-- <script type="text/javascript" src="https://yastatic.net/jquery/3.3.1/jquery.min.js" crossorigin="anonymous"></script> --}}
{{-- <script type="text/javascript" src="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js"></script> --}}
{{-- <script src="{{ asset('site/js/jquery.js') }}"></script> --}}
{{-- <script src="https://yastatic.net/jquery/3.3.1/jquery.min.js"></script> --}}
{{-- <script src="{{ asset('site/js/main.js') }}"></script> --}}
{{-- <script type="text/javascript" src="https://vk.com/js/api/openapi.js?167"></script>
    <script type="text/javascript">
        setTimeout(() => {
            VK.Widgets.CommunityMessages("vk_community_messages", 25381804, {
                expandTimeout: "500000",
                tooltipButtonText: "Есть вопрос?"
            });
        }, 10000)
    </script> --}}

    {{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script> --}}
    
</html>
