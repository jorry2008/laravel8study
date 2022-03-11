<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Echo Test</title>

    <!-- Fonts -->
{{--    <script src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>--}}

    <!-- Styles -->
</head>
<body>
<div id="app" class="flex-center position-ref full-height">
    Echo
</div>
<script>
    window.id = undefined;

    @if(!empty(Auth::user()))
        window.id = "{{Auth::user()->id}}"
    @endif
</script>
<script src="/js/app.js"></script>
</body>
</html>
