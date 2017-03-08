<!-- Top Bar -->
<div id="topBar">
    <div class="container">
        <!-- right -->
        <ul class="top-links list-inline pull-right">
            <li class="text-welcome hidden-xs">Welcome to Smarty, <strong>John Doe</strong></li>
            <li class="hidden-xs"><a href="page-login-1.html">LOGIN</a></li>
            <li class="hidden-xs"><a href="page-register-1.html">REGISTER</a></li>
            <li class="hidden-xs img-link">
                <a href="page-register-1.html"><img src="<?=get_stylesheet_directory_uri();?>/assets/img/divestmedia-top-logo.png" class="img-responsive"></a>
            </li>
        </ul>
        <!-- left -->
        <ul class="top-links list-inline">
            <li class="hidden-xs"><a href="page-faq-1.html" class="font-lato start-btn primary-bg weight-700 text-white">start a campaign</a></li>
        </ul>
    </div>
</div>
<!-- /Top Bar -->
<div id="header" class="sticky header-smx clearfix">
    <!-- TOP NAV -->
    <header id="topNav">
        <div class="container">
            <!-- Mobile Menu Button -->
            <button class="btn btn-mobile" data-toggle="collapse" data-target=".nav-main-collapse">
                <i class="fa fa-bars"></i>
            </button>
            <!-- BUTTONS -->
            <ul class="pull-right nav nav-pills nav-second-main">
                <li>
                    <a href="#" class="active">
                        <i class="fa fa-facebook"></i>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa fa-twitter"></i>
                    </a>
                </li>
            </ul>
            <!-- /BUTTONS -->
            <!-- Logo -->
            <a class="logo pull-left" href="#">
                <img src="<?=get_stylesheet_directory_uri();?>/assets/img/investment-injection-logo.png" alt="" />
            </a>
            <!-- 
							Top Nav 
							
							AVAILABLE CLASSES:
							submenu-dark = dark sub menu
						-->
            <div class="navbar-collapse pull-right nav-main-collapse collapse">
                <nav class="nav-main">
                    <ul id="topMain" class="nav nav-pills nav-main">
                        <li class="dropdown">
                            <!-- HOME -->
                            <a class="dropdown-toggle" href="#">
											HOME
										</a>
                            <ul class="dropdown-menu">
                                <li class="dropdown">
                                    <a class="dropdown-toggle" href="#">
													SUBLINK
												</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">SUBMENU LINK</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <!-- /Top Nav -->
</div>
