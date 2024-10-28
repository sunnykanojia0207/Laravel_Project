<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ًApplication</title>
        @vite('resources/css/app.css')
        <script>
            window.baseurl="{{ url('/') }}"
        </script>
    </head>
    <body>
        <div id="app">
            {{-- <router-view></router-view> --}}
        </div>
        @vite('resources/js/app.js')
    </body>
</html>
