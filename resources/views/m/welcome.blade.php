@extends('m.base')

@section('title')
<title>Jnanagni | FET Techfest 2017</title>
@endsection

@section('style')	
<link rel="stylesheet" href="/methusael/jnanagni/public/mb/css/welcome.css">	
@endsection

@section('script')
<script src="/methusael/jnanagni/public/mb/js/welcome.js" ></script>
@endsection

@section('header-text')
Jnanagni
@endsection

@section('content')
<div class="left" id="orb-panel">
	<div class="orb" id="orb-dtg">
	<canvas class="progress" id="arc"></canvas>
		<div class="progress" id="text">
			<div class="top">
				<span id="prog"></span>
			</div>
			<div class="bottom">Days to go</div>
		</div>
	</div>
</div>
<div class="right">
	<span class="title">JNANAGNI</span>
	<span class="subtitle">The Fire of Wisdom</span>        
	<span class="note-info"> This site is best viewed on Desktop</span>
</div>
@endsection
<div class="footer ">JNANAGNI <span>/ BETA</span></div>
