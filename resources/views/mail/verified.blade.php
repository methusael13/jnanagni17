<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Email Verified</title>

    <link href="https://jnanagni17.in/css/verified.css" rel="stylesheet" type="text/css" />    
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu+Condensed" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300" rel="stylesheet">
</head>
<body>
    <div class="content">
        <div class="logo">JNANAGNI</div>
        <div class="title">
            <span id="tq">Thank You,</span>&nbsp
            <span id="name">{{ $first_name . ' ' . $last_name }}</span>
        </div>
        <div class="text">
            You are good to go, your email has been verified.&nbspYou can now continue on to register for events you like.
        </div>
        <div class="button-area">
            <a href="http://jnanagni17.in" id="button-link">Continue</a>
        </div>
    </div>
</body>
</html>
