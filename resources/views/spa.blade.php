<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <title>Galley</title>
</head>
<body>
<div class="container-fluid">
    <div class="content">
        <div id="root"></div>
        <noscript>{{ __('Enable JavaScript.') }}</noscript>
    </div>
</div>
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
