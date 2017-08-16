<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>@yield('title')</title>
        <link rel="icon" type="image/x-icon" href="favicon.ico" />

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Ubuntu+Condensed" rel="stylesheet">
        @yield('fonts')

        <!-- Styles -->
        <link rel="stylesheet" type="text/css" href="css/reg/layout.css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        @yield('style')

        <!-- Scripts -->
        <script src="js/jquery-3.1.1.min.js"></script>
        <script src="js/reg/layout.js"></script>
        @yield('script')
    </head>
    <body>
        @yield('content')
    </body>
</html>

