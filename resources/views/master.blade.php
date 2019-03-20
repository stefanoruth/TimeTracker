<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <meta name="description" content="TimeTracking">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ mix('app.css') }}">
</head>

<body class="">
    @yield('body')
    <noscript>
        For full functionality of this site it is necessary to enable JavaScript.
        Here are the <a href="https://www.enable-javascript.com/" target="_blank" rel="noopener">
        instructions how to enable JavaScript in your web browser</a>.
    </noscript> @routes
    <script async src="{{ mix('app.js') }}"></script>
</body>

</html>
