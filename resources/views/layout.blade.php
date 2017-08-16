<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @yield('title')

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Ranga:700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
        @yield('fonts')

        <!-- Styles -->
        <link href="css/layout.css" rel="stylesheet" type="text/css">
        @yield('style')

        <!-- Scripts -->
        <script src="js/snap.svg.js"></script>
        <script src="js/layout.js"></script>
        <script src="https://use.fontawesome.com/3d20e1b92f.js"></script>
        @yield('script')
    </head>
    <body>
        <div class="header">
            <div class="header-content page-content-margin">
                <div class="logo">JNANAGNI</div>
                <div class="reg">
                    <a href="#" title="Register" onclick="regAction(); return false;">
                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                    </a>
                    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                    <a href="#" title="Login" onclick="regAction(); return false;">
                        <i class="fa fa-sign-in" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="sidebar sidebar-hidden" id="sidebar-menu">
            <div class="menu">
                <a href="#"><i class="fa fa-question-circle" aria-hidden="true"></i><span>FAQ</span></a>
                <a href="#"><i class="fa fa-calendar" aria-hidden="true"></i><span>Schedule</span></a>
                <a href="#"><i class="fa fa-cubes" aria-hidden="true"></i><span>Sponsors</span></a>
                <a href="#"><i class="fa fa-info" aria-hidden="true"></i><span>About Us</span></a>
                <a href="#"><i class="fa fa-paper-plane" aria-hidden="true"></i><span>Contact</span></a>
            </div>
        </div>
        <div class="content" id="content-page">
            <div class="content-info">
                <div class="reg-dialog modal hidden" id="reg-dialog">
                    <div class="section-reg">
                        <div class="section-header">Register</div>
                        <div class="section-content">
                            <form method="POST" class="form" id="form-reg" action="#">
                                <div id="name">
                                    <div class="input-div">
                                        <input class="input-field" type="text" name="first-name" />
                                        <label class="input-div-label">First Name</label>
                                    </div>
                                    <div class="input-div">
                                        <input class="input-field" type="text" name="last-name" />
                                        <label class="input-div-label">Last Name</label>
                                    </div>
                                </div>
                                <div class="input-div">
                                    <input class="input-field" type="email" name="email" />
                                    <label class="input-div-label">Email</label>
                                </div>
                                <div class="input-div">
                                    <input class="input-field" type="password" name="passwd" />
                                    <label class="input-div-label">Password</label>
                                </div>
                                <div class="input-div">
                                    <input class="input-field" type="password" name="passwd-rep" />
                                    <label class="input-div-label">Confirm Password</label>
                                </div>
                                <div id="gender">
                                    <input type="radio" name="gender" id="radio-male" checked/><label for="radio-male">Male</label>
                                    <input type="radio" name="gender" id="radio-female"/><label for="radio-female">Female</label>
                                </div>
                                <div class="input-div">
                                    <input class="input-field" type="text" name="college" />
                                    <label class="input-div-label">College</label>
                                </div>
                                <input type="submit" name="submit" value="Register" id="but-reg"/>
                            </form>
                        </div>
                    </div>
                    <div class="section-login">
                        <div class="section-header">Login</div>
                        <div class="section-content">
                            <form method="POST" class="form" id="form-login" action="#">
                                <div id="user-img"><img src="res/images/lock.svg"></div>
                                <div class="input-div">
                                    <input class="input-field" type="email" name="email" />
                                    <label class="input-div-label">Email</label>
                                </div>
                                <div class="input-div">
                                    <input class="input-field" type="password" name="passwd" />
                                    <label class="input-div-label">Password</label>
                                </div>
                                <input type="submit" name="submit" value="Login" id="but-login"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @yield('content')
        </div>
        <div class="svg-icon"><svg id="menu-icon"></svg></div>
        <div class="share-section">
            <i class="fa fa-share-alt share-icon" aria-hidden="true" ></i>
            <div class="share-opts">
                <a href="#" id="sfb"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                <a href="#" id="stw"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                <a href="#" id="sgp"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
                <a href="#" id="syt"><i class="fa fa-youtube" aria-hidden="true"></i></a>
            </div>
        </div>
        <div class="overlay hidden" id="no-click-overlay"></div>
    </body>
</html>
