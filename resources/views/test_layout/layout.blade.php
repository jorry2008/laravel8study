<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full font-sans antialiased">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Cache" content="no-cache">
    <link data-n-head="ssr" rel="icon" type="image/png"  href="favicon.jpg">
    <title>{{ trans('admin::auth.signin_account') }} | {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased">
<div class="min-h-full flex relative">
    <div class="flex-1 flex flex-col justify-center py-20 px-8 sm:px-6 lg:flex-none lg:px-20 xl:px-24 xl:ml-32 relative z-20 backdrop-blur-sm bg-white/50">
        <div class="mx-auto w-full max-w-sm lg:w-96">
            @yield('content')
        </div>
    </div>

    <div class="fixed top-0 left-0 w-full h-full z-0">
        <img class="absolute inset-0 h-full w-full object-cover" src="images/BILL6179.jpg" alt="">
    </div>
</div>
</body>
</html>
