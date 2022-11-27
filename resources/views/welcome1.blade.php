<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head class="scroll-smooth">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>27 проект</title>

    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}


    {{-- <link href="{{ asset('css/app.css') }}?s=2208120328{{ rand() }}" rel="stylesheet" /> --}}
    <link rel="stylesheet" href="{{ asset('storage/css/ionicons.min.css') }}" />

    {{-- <link rel="stylesheet" href="{{ asset('storage/css/style.css') }}?s=2208120328" /> --}}
    {{-- <link rel="icon" type="image/png" href="/storage/site/img/logo47.png" /> --}}

    {{-- @vite('resources/css/app.css') --}}

    {{-- <meta property="og:title" content="Авто-АС запчасти"> --}}
    {{-- <meta property="og:site_name" content="avto-as.ru"> --}}
    {{-- <meta property="og:url" content="https://avto-as.ru"> --}}
    {{-- <meta property="og:description" content="Запчасти для автомобилей"> --}}
    {{-- <meta property="og:image" content="https://22.avto-as.ru/storage/site/img/logo2112.png"> --}}
    {{-- <meta property="og:image:width" content="968"> --}}
    {{-- <meta property="og:image:height" content="504"> --}}


    <style>
        body {
            background-color: rgb(150, 150, 150);
            line-height: 5px;
        }

        div {
            display: inline-block;
            xborder: 1px;
            padding: 0;
            margin: 0;
        }

        body>div {
            xdisplay: block;
            xwhite-space: pre;
        }

        body>div>div {
            display: inline-block;
            height: 4px;
            width: 4px;
            overflow: hidden;
        }

        .nobr {}
    </style>

</head>

<body>

    {{-- 1111 {{ $gg }} --}}
    {{-- <br/> 22 {{ $color }} --}}
    {{-- <br/> 221 {{ print_r($colors) }} --}}

    {{-- time {{ $time }} --}}
    time
    <pre>{{ print_r($time0) }}</pre>

    @if (1 == 1)
        показ картинки
        <br />
        <br />

        <nobr>
            @foreach ($grayArray as $y => $v1)
                {{-- <div> --}}
                {{-- <td>y: {{ $y }}</td> --}}
                @foreach ($v1 as $x => $v)
                    <div style="background-color: rgba(0,0,0,{{ 1 - round($v / 255, 1) }})"
                        title="{{ 1 - round($v / 255, 1) }}">&nbsp;
                    </div>
                @endforeach
                <br />
            @endforeach
            {{-- </table> --}}
        </nobr>
        {{-- <br/> 221 {{ print_r($color11) }} --}}
    @endif






</body>
{{-- <script src="{{ asset('js/app.js') }}?s=2208130539{{ rand() }}"></script> --}}

</html>
