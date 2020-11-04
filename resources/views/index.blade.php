<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Календарь</title>
    <link href="{{mix('css/app.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/index.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/preloader.css')}}" rel="stylesheet" type="text/css">
</head>
<body>
<div id="root"></div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>