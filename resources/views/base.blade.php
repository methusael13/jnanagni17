<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="fritolay" @if ($loggedin) content="true" @else content="false" @endif />
        @yield('title')
        <link rel="icon" type="image/x-icon" href="favicon.ico" />

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Ubuntu+Condensed" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=PT+Mono" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400" rel="stylesheet">
        @yield('fonts')

        <!-- Styles -->
        <link rel="stylesheet" type="text/css" href="css/base.css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        @yield('style')

        <!-- Scripts -->
        <script src="js/jquery-3.1.1.min.js"></script>
        <script src="js/preloader.js"></script>
        <script src="js/vector-min.js"></script>
        <script src="js/base.js"></script>
        <script src="js/constellation.js"></script>
        @yield('script')
    </head>
    <body>
        <div class="loader-wrapper full-screen animatable-o2" id="loader-wrapper">
            <canvas class="center-page" id="loader" width="180" height="180"></canvas>
            <div class="center-page" id="loader-text">Jnanagni</div>
        </div>
        <div class="hidden" id="pre-load-bg">
            <img src="res/images/backgrounds/stage.jpg" alt="">
            <img src="res/images/backgrounds/concert.jpg" alt="">
            <img src="res/images/backgrounds/copter.jpg" alt="">
            <img src="res/images/popup.jpg" alt="">
            @foreach ($evtcats as $cat)
            <img src={{ "res/images/event-cat/" . $cat->getID() . ".jpg" }} alt="">
            @endforeach
        </div>
        <div class="crossfade">
            <div class="slide slideshow-anim full-screen"></div>
            <div class="slide slideshow-anim full-screen"></div>
            <div class="slide slideshow-anim full-screen"></div>
            <div class="slide slideshow-anim full-screen"></div>
            <div class="pre-slide full-screen"></div>
        </div>
        <div class="header">
            <div class="header-content page-content-margin" id="hc-id">
                <div class="menu" id="menu-id">
                    <img src="res/images/jnanagni-03.png" width="50" height="50">
                    <div class="nav">
                        <a href="#" id="n-schedule"><span>Schedule</span></a>
                        <!-- <a href="#" id="n-faq"><span>FAQ</span></a> -->
                        <a href="#" id="n-sponsors"><span>Sponsors</span></a>
                        <a href="#" id="n-about-us"><span>About Us</span></a>
                        <a href="#" id="n-contact"><span>Contacts</span></a>
                    </div>
                </div>
                <div class="reg" id="reg-id">
                    <a href="https://www.facebook.com/JnanagniOfficial/" class="share" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                    <a href="https://twitter.com/jnanagni_fet" class="pad share" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                    <a href="https://plus.google.com/+JnanagniOfficial" class="pad share" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
                    <a href="https://www.youtube.com/channel/UCnfEkki1z7QMfqpnJOGVCJQ" class="pad share" target="_blank"><i class="fa fa-youtube" aria-hidden="true"></i></a>
                    <a href="#" @if ($loggedin) class="pad2 pre-reg animatable-co remove-o hidden" @else class="pad2 pre-reg animatable-co" @endif id="pre-reg-but">
                        Login | Register
                    </a>
                    <div @if ($loggedin) class="pad2 pre-reg animatable-all" @else class="pad2 pre-reg animatable-all remove-o hidden" @endif id="user-btn">
                        <span class="btn-icon"><i class="fa fa-user-o" aria-hidden="true"></i></span>
                        <span class="btn-text" id="user-btn-text">{{ $fname }}</span>
                        <div class="dropdown-content">
                            <div class="dropdown-item" id="user-logout">
                                <i class="fa fa-power-off" aria-hidden="true"></i><span>Logout</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content" id="content-page">
            <div class="content-info">
                <div class="reg-dialog modal animatable-o remove-o hidden" id="reg-dialog">
                    <div class="section-reg">
                        <div class="section-header">
                        <span class="round-btn"><i class="fa fa-arrow-left" aria-hidden="true"></i></span>
                        <div id="form-sh-login" class="form-sh-btn animatable-c form-sh-active">Login</div>&nbsp/&nbsp
                        <div id="form-sh-reg" class="form-sh-btn animatable-c">Register</div>
                        </div>
                        {{ Form::open(['url' => '/pre-register', 'method' => 'POST', 'class' => 'form animatable-o remove-o hidden', 'id' => 'form-reg']) }}
                            <div class="inp-section">
                                <div class="inp-two">
                                    <div class="input-div">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                        <input class="input-field" type="text" name="first-name" data-validation="name" required/>
                                        <label class="input-div-label">First Name</label>
                                    </div>
                                    <div class="input-div">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                        <input class="input-field" type="text" name="last-name" data-validation="name-nr"/>
                                        <label class="input-div-label">Last Name</label>
                                    </div>
                                </div>
                                <div class="input-div">
                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                    <input class="input-field" type="email" name="email" data-validation="email" id="_email" required/>
                                    <label class="input-div-label">Email</label>
                                </div>
                                <div class="input-div">
                                    <i class="fa fa-phone" aria-hidden="true"></i>
                                    <input class="input-field" type="text" name="phone" data-validation="phone" required/>
                                    <label class="input-div-label">Phone (10 digits)</label>
                                </div>
                                <div class="inp-two">
                                    <div class="input-div">
                                        <i class="fa fa-lock" aria-hidden="true"></i>
                                        <input class="input-field" type="password" name="password" data-validation="password" required/>
                                        <label class="input-div-label">Password</label>
                                    </div>
                                    <div class="input-div">
                                        <i class="fa fa-lock" aria-hidden="true"></i>
                                        <input class="input-field" type="password" name="conf-password" data-validation="conf-password" required/>
                                        <label class="input-div-label">Confirm Password</label>
                                    </div>
                                </div>
                                <div class="input-div">
                                    <i class="fa fa-university" aria-hidden="true"></i>
                                    <input class="input-field" type="text" name="college" data-validation="text" required/>
                                    <label class="input-div-label">College</label>
                                </div>
                                <div class="input-div" id="gender">
                                    <label class="input-div-label" id="gender-label">Gender:</label>
                                    <div class="input-radio animatable-all" id="ir-female">
                                        <div class="input-radio-img animatable-all"><i class="fa fa-female" aria-hidden="true"></i></div>
                                        Female
                                    </div>
                                    <div class="input-radio animatable-all ir-active" id="ir-male">
                                        <div class="input-radio-img animatable-all"><i class="fa fa-male" aria-hidden="true"></i></div>
                                        Male
                                    </div>
                                </div>
                            </div>
                            <input type="submit" name="submit" value="Register" id="but-reg" />
                        {{ Form::close() }}
                        {{ Form::open(['url' => '/login', 'method' => 'POST', 'class' => 'form animatable-o', 'id' => 'form-login']) }}
                            <div class="input-div">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <input class="input-field" type="email" name="fritolay" data-validation="email" id="login_email" required/>
                                <label class="input-div-label">Email</label>
                            </div>
                            <div class="input-div">
                                <i class="fa fa-lock" aria-hidden="true"></i>
                                <input class="input-field" type="password" name="kingfisher" data-validation="password" id="login_pass" required/>
                                <label class="input-div-label">Password</label>
                            </div>
                            <input type="submit" name="submit" value="Login" id="but-login" />
                        {{ Form::close() }}
                    </div>
                    <div class="section-info">
                        <div class="logo animatable-o2" id="join-msg-logo"></div>
                        <div class="info-msg animatable-o2 remove-o hidden" id="reg-info-msg"></div>
                        <div id="resend-email" class="animatable-o remove-o">Accomodation and fooding available.<br>Contact <b>Mohit Singh</b>: <i>+918090133905</i><br><br>
                        Didn't receive the confirmation email yet? Just fill the email field on the left and click &nbsp<span><a href="#" id="mail-resend">Resend</a></span></div>
                    </div>
                </div>
            </div>
        @yield('content')
        </div>
        <div class="nav-dialog modal animatable-o remove-o hidden" id="nav-dialog-menu">
            <div class="pic-panel">
                <div class="nav-title hidden" id="nt-schedule">Schedule</div>
                <div class="nav-title hidden" id="nt-sponsors">Sponsors</div>
                <div class="nav-title" id="nt-about-us">About Us</div>
                <div class="nav-title hidden" id="nt-contact">Contacts</div>
                <div class="nav-title hidden" id="nt-dev">Developers</div>
            </div>
            <div class="nav-content">
                <div class="nav-header-menu">
                    <div class="item" title="Schedule" id="ni-schedule"><i class="fa fa-clock-o" aria-hidden="true"></i></div>
                    <div class="item" title="Sponsors" id="ni-sponsors"><i class="fa fa-rocket" aria-hidden="true"></i></div>
                    <div class="item active" title="About Us" id="ni-about-us"><i class="fa fa-university" aria-hidden="true"></i></div>
                    <div class="item" title="Contacts" id="ni-contact"><i class="fa fa-at" aria-hidden="true"></i></div>
                    <div class="item" title="Developers" id="ni-dev"><i class="fa fa-code" aria-hidden="true"></i></div>
                </div>
                <div class="nav-info hidden" id="nf-schedule">@include('schedule')</div>
                <div class="nav-info hidden" id="nf-sponsors">
                <?php $sp_cnt = $sponsors['count']; ?>
                @for ($i = 0; $i < $sp_cnt; $i++)
                    <div class="square-item animatable-f" style='background-image: url("{{ $sponsors["path"] . $i . ".jpg" }}");'></div>
                @endfor
                </div>
                <div class="nav-info" id="nf-about-us">
                @php
                    echo nl2br(file_get_contents('storage/about-us.cms'));
                @endphp
                </div>
                <div class="nav-info hidden" id="nf-contact">
                    <div class="nf-contact-section" id="nfcs-ft">
                        <div class="nf-contact-section-title"><div>Faculty</div></div>
                        <div class="nf-contact-section-data">
                        @foreach ($ftContacts as $contact)
                        <div class="contact-item">
                            <div id="name">{{ $contact[0] }}</div>
                            <div id="phone">(+91)&nbsp{{ $contact[1] }}</div>
                            <div id="title">{{ $contact[2] }}</div>
                        </div>
                        @endforeach
                        </div>
                    </div>
                    <div class="nf-contact-section">
                        <div class="nf-contact-section-title"><div>Students</div></div>
                        <div class="nf-contact-section-data">
                        @foreach ($contacts as $contact)
                        <div class="contact-item">
                            <div id="name">{{ $contact[0] }}</div>
                            <div id="phone">(+91)&nbsp{{ $contact[1] }}</div>
                            <div id="title">{{ $contact[2] }}</div>
                        </div>
                        @endforeach
                        </div>
                    </div>
                </div>
                <div class="nav-info hidden" id="nf-dev">
                    <div class="nf-contact-section">
                        <div class="nf-contact-section-data">
                        @foreach ($developers as $dev)
                        <div class="contact-item">
                            <div class="dp-section">
                                <div class="dp" style='background-image: url("{{ $dev[3] }}");'></div>
                            </div>
                            <div id="name">{{ $dev[0] }}</div>
                            <div id="phone">{{ $dev[1] }}</div>
                            <div id="title">{{ $dev[2] }}</div>
                        </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('evt-details-dialog')
        <div class="overlay hidden" id="no-click-overlay"></div>
        <div class="bmark bm-align-tl" id="bma-0"></div>
        <div class="bmark bm-align-tr" id="bma-1"></div>
        <div class="bmark bm-align-bl" id="bma-2"></div>
        <div class="bmark bm-align-br" id="bma-3"></div>
        <div class="hud-element" id="event-date">23 | 24 | 25&nbsp&nbsp<span>/ March</span></div>
        <div class="constellation-wrapper"><canvas id="constellation"></canvas></div>
        <div class="tooltip" id="btn-launch-tooltip">Click here to Launch</div>
        <div class="modal animatable-o remove-o hidden" id="piyush-popup"></div>
    </body>
</html>

