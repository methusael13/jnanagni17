@extends('m.base')

@section('title')
<title>About Us</title>
@endsection

@section('header-text')
About Us
@endsection

@section('style')	
<style type="text/css">
	.content-info {
	font-family: 'Source Sans Pro';
	color: #e6e6e6;
	font-size: 0.9em;
	height: 100%;
	box-sizing: border-box;
	padding: 1em 1.5em;
}
</style>	
@endsection

@section('content')
<div class="content-info">
	<?php echo nl2br(file_get_contents("storage/about-us.cms")); ?>
</div>
@endsection
