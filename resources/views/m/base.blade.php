<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">	
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	@yield('title')
	<link rel="icon" type="image/x-icon" href="favicon.ico" />
	
	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Ubuntu+Condensed" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=PT+Mono" rel="stylesheet">
	@yield('fonts')

	<!-- Styles -->
	<link rel="stylesheet" href="/methusael/jnanagni/public/mb/css/base.css">
	<link href='https://fonts.googleapis.com/css?family=Raleway:300' rel='stylesheet' type='text/css'>
	@yield('style')

	<!-- Scripts -->
	<script src="/methusael/jnanagni/public/js/jquery-3.1.1.min.js"></script>
	<script src="/methusael/jnanagni/public/mb/js/base.js"></script>
	<script src="https://use.fontawesome.com/3d20e1b92f.js" async></script>
	@yield('script')

</style>
</head>
<body> 
	<div class="crossfade">
		<div class="slide full-screen"></div><div class="slide full-screen"></div>
		<div class="slide full-screen"></div><div class="slide full-screen"></div>
		<div class="pre-slide full-screen"></div>
	</div>

	<div class="header" > 
		<a class="header-btn" id="open-sidenav"><i class=" fa fa-bars" aria-hidden="true"></i></a>
		<span class="header-text" id="header-text">@yield('header-text')</span>
		<a class="header-btn" id="login-btn"> <i class="fa fa-sign-in" aria-hidden="true"></i></a>		
	</div>
	<div id="mySidenav" class="sidenav hidden">
		<div class="menu">
			<a href="/methusael/jnanagni/public/mu" class="nav-item"><i class="fa fa-home" aria-hidden="true"></i><span class="nav-text">Home</span></a>
			<a href="/methusael/jnanagni/public/mu/about-us" class="nav-item"><i class="fa fa-university" aria-hidden="true"></i><span class="nav-text">About Us</span></a>
			<a href="/methusael/jnanagni/public/mu/sponsors" class="nav-item"><i class="fa fa-rocket" aria-hidden="true"></i><span class="nav-text">Sponsors</span></a>
			<a href="/methusael/jnanagni/public/mu/contact-us" class="nav-item"><i class="fa fa-phone" aria-hidden="true"></i><span class="nav-text">Contact</span></a>
		</div>
		<div class="share">
			<div class="nav-item">
				<a href="https://www.facebook.com/JnanagniOfficial/" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
				<a href="https://twitter.com/jnanagni_fet" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i> </a>
				<a href="https://plus.google.com/+JnanagniOfficial" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
				<a href="https://www.youtube.com/channel/UCnfEkki1z7QMfqpnJOGVCJQ" target="_blank"><i class="fa fa-youtube" aria-hidden="true"></i></a>
			</div>
		</div>
	</div>
	<div class="overlay hidden" id="overlay"></div>
	<div class="content-all">
		<div class="section-reg hidden" id="login-section">
			<div class="section-header">Pre-Register</div>
			<div class="section-content">
				{{ Form::open(['url' => '/pre-register', 'method' => 'POST', 'class' => 'form', 'id' => 'form-reg']) }}
				<div class="input-div">
					<input class="input-field" type="text" name="first-name" data-validation="name" required/>
					<label class="input-div-label">First Name</label>
				</div>
				<div class="input-div">
					<input class="input-field" type="text" name="last-name" data-validation="name-nr"/>
					<label class="input-div-label">Last Name</label>
				</div>
				<div class="input-div">
					<input class="input-field" type="email" name="email" data-validation="email" required/>
					<label class="input-div-label">Email</label>
				</div>
				<div class="input-div">
					<input class="input-field" type="text" name="phone" data-validation="phone" required/>
					<label class="input-div-label">Phone (10 digits)</label>
				</div>
				<div class="input-div">
					<input class="input-field" type="text" name="college" data-validation="text" required/>
					<label class="input-div-label">College</label>
				</div>
				<div class="info-msg animatable-o remove-o hidden" id="reg-info-msg"></div>
				<input type="submit" name="submit" value="Register" id="but-reg" />
				{{ Form::close() }}
            </div>
        </div>
		@yield('content')	
	</div>
</body>
</html>
