<header class="header sticky d-none">
    <div class="header-bottom bg-white gradient-flare">
        <div class="container">
            <div class="header-bottom-area">
                <!-- <div class="logo bg-white pt-1 pb-1 ps-3 pe-4 rounded-pill shadow"> -->
                <div class="logo">
                    <a href="<?php echo ROOT_PATH; ?>/">
                        <img src="<?php echo ROOT_PATH; ?>/assets/images/logo-new.png" alt="logo">
                    </a>
                </div> <!-- Logo End -->

                <div class="header-trigger-wrapper d-flex d-lg-none align-items-center">
                    <div class="header-trigger d-block d-lg-none">
                        <span></span>
                    </div>
                    <div class="account-cart-wrapper">
                        <a class="account" href="login.php"><i class="las la-user"></i></a>
                    </div>
                </div> <!-- Trigger End-->

                <ul class="menu">
                    <li><a href="<?php echo ROOT_PATH; ?>/">Home</a></li>
                    <li><a href="<?php echo ROOT_PATH; ?>/about-us.php">About</a></li>
                    <!-- <li><a href="product.php">Services</a></li> -->
                    <li><a href="<?php echo ROOT_PATH; ?>/vision-mission.php">Vision & Mission</a></li>
                    <!-- <li><a href="<?php echo ROOT_PATH; ?>/business-plan.php">Business Plan</a></li> -->
                    <li><a href="<?php echo ROOT_PATH; ?>/product.php">Product</a></li>

                    <!-- <li><a href="faq.php">Faq</a></li> -->
                    <li><a href="<?php echo ROOT_PATH; ?>/contact.php">Contact</a></li>

                    <li class="account-cart-wrapper d-none d-lg-block">
                        <a class="account" href="<?php echo ROOT_PATH; ?>/login.php"><i class="las la-user"></i></a>
                    </li>
                </ul> <!-- Menu End -->
            </div>
        </div>
    </div>
</header>

<div class="navbar-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg">
                    <a class="navbar-brand d-flex d-inline-block justify-content-center align-items-center" href="./">
                        <img src="<?php echo ROOT_PATH; ?>/assets/images/logo-new.png" alt="Logo" style="width: 80px;border-top-left-radius: 25px;border-bottom-left-radius: 25px;">
                        <!-- <h2 class="text-danger bg-white p-3" style="border-top-right-radius: 25px;border-bottom-right-radius: 25px;">Maxizone</h2> -->
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="toggler-icon"></span>
                        <span class="toggler-icon"></span>
                        <span class="toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                        <ul id="nav" class="navbar-nav ml-auto">
                            <li class="nav-item active">
                                <a class="page-scroll" href="#home">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="page-scroll" href="#services">Services</a>
                            </li>
                            <li class="nav-item">
                                <a class="page-scroll" href="#about">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="page-scroll" href="#contact">Contact</a>
                            </li>
                            <!-- 
                            <li class="nav-item">
                                <a class="page-scroll" href="#team">Team</a>
                            </li>
                            <li class="nav-item">
                                <a class="page-scroll" href="#blog">Blog</a>
                            </li> -->
                        </ul>
                    </div> <!-- navbar collapse -->

                    <!-- <div class="navbar-btn d-none d-inline-block">
                        <a class="main-btn" data-scroll-nav="0" href="user/login.php">Login</a>
                        <a class="main-btn bg-success" style="background: greenyellow;" data-scroll-nav="0" href="user/register.php">Register</a>
                    </div> -->
                </nav> <!-- navbar -->
            </div>
        </div> <!-- row -->
    </div> <!-- container -->
</div>