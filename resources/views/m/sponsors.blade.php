@extends('m.base')

@section('title')
<title>Sponsors</title>
@endsection

@section('header-text')
Past Sponsors
@endsection

@section('style')	
<style type="text/css">
	.square-item {
		min-width: 9em; min-height: 9em;
		margin: .5em;
		background-size: contain;
		background-position: center center;

	}
	.content-all {
		
		flex-wrap: wrap;
		flex-direction: row !important; 
	}
</style>
@endsection

@section('content')
<?php $sp_cnt = $sponsors['count'] ?>
@for ($i = 0; $i < $sp_cnt; $i++)
	<div class="square-item " style='background-image: url("/methusael/jnanagni/public/{{$sponsors["path"] . $i . ".jpg" }}");'></div>
@endfor
@endsection
