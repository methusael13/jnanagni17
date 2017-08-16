@extends('layout')

@section('title')
<title>Jnanagni | FET Techfest 2017</title>
@endsection

@section('style')
<link href="css/index.css" rel="stylesheet" type="text/css">
@endsection

@section('script')
<script src="js/script.js"></script>
@endsection

@section('content')
    <div class="svg-wrapper">
        <svg id="svg-isomap" class="svg" preserveAspectRatio="xMidYMid meet" viewBox="0 0 800 500"></svg>
    </div>
    <div class="panel-right">
        <div class="date-info">
            <span class="date-label" id="when">When?</span>
            <div>
                <span class="date-text" id="moy">24<sup>th</sup> FEB,  </span>
                <span class="date-text" id="y">2017</span>
            </div>
            <div>
                <span class="date-text" id="dow">FRIDA</span>
                <span class="date-text" id="dowy">Y</span>
            </div>
            <!-- <span class="date-text" id="msg">Be there!</span> -->
        </div>
        <div class="desc">
            <span class="desc-text" id="college">Faculty of Engineering and Technology</span>
        </div>
    </div>
@endsection
