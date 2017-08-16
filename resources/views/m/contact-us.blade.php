@extends('m.base')

@section('title')
<title>Contact Us</title>
@endsection

@section('header-text')
contact us
@endsection

@section('style')	
	<link rel="stylesheet" type="text/css" href="/methusael/jnanagni/public/mb/css/contact-us.css">
@endsection

@section('content')
<div class="nav-info" id="nf-contact">
    @foreach ($contacts as $contact)
    <div class="contact-item">
        <div id="name">{{ $contact[0] }}</div>
        <div id="phone">(+91)&nbsp{{ $contact[1] }}</div>
        <div id="title">{{ $contact[2] }}</div>
    </div>
    @endforeach
</div> 
@endsection
