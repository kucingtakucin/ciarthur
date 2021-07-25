<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/images/favicon.png">
    <title>Snowlake</title>
    <link rel="stylesheet" type="text/css" href="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/css/plugins.css">
    <link rel="stylesheet" type="text/css" href="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/revolution/css/settings.css">
    <link rel="stylesheet" type="text/css" href="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/revolution/css/layers.css">
    <link rel="styleslaeet" type="text/css" href="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/revolution/css/navigation.css">
    <link rel="stylesheet" type="text/css" href="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/type/type.css">
    <link rel="stylesheet" type="text/css" href="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style.css">
    <link rel="stylesheet" type="text/css" href="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/css/color/purple.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,300i,400,400i,600,600i,700,700i&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/css/font/font2.css">
</head>

<body>
    <div class="content-wrapper">
        <nav class="navbar absolute transparent inverse-text navbar-hover-opacity nav-uppercase navbar-expand-lg">
            <div class="container flex-row justify-content-center">
                <div class="navbar-brand"><a href="<?= base_url() ?>"><img src="#" srcset="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/images/logo.png 1x, https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/images/logo@2x.png 2x" class="logo-dark" alt="" /><img src="#" srcset="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/images/logo-light.png 1x, https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/images/logo-light@2x.png 2x" class="logo-light" alt="" /></a></div>
                <div class="navbar-other ml-auto order-lg-3">
                    <ul class="navbar-nav flex-row align-items-center" data-sm-skip="true">
                        <li class="nav-item">
                            <div class="navbar-hamburger d-lg-none d-xl-none ml-auto"><button class="hamburger animate plain" data-toggle="offcanvas-nav"><span></span></button></div>
                        </li>
                        <li class="dropdown search-dropdown position-static nav-item">
                            <a href="#" role="button" class="collapse-toggle" data-toggle="collapse" data-target=".search-dropdown-menu" aria-haspopup="true" aria-expanded="false"><i class="jam jam-search"></i></a>
                            <div class="dropdown-menu search-dropdown-menu w-100 collapse">
                                <div class="form-wrapper">
                                    <form class="inverse-text">
                                        <input type="text" class="form-control" placeholder="Search something">
                                    </form>
                                    <!-- /.search-form -->
                                    <i class="dropdown-close jam jam-close"></i>
                                </div>
                                <!-- /.form-wrapper -->
                            </div>
                        </li>
                        <li class="nav-item"><button class="plain" data-toggle="offcanvas-info"><i class="jam jam-info"></i></button></li>
                    </ul>
                    <!-- /.navbar-nav -->
                </div>
                <!-- /.navbar-other -->
                <div class="navbar-collapse offcanvas-nav">
                    <div class="offcanvas-header d-lg-none d-xl-none">
                        <a href="<?= base_url() ?>"><img src="#" srcset="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/images/logo-light.png 1x, https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/images/logo-light@2x.png 2x" alt="" /></a>
                        <button class="plain offcanvas-close offcanvas-nav-close"><i class="jam jam-close"></i></button>
                    </div>
                    <?php $this->load->view('templates/frontend/navbar') ?>
                    <!-- /.navbar-nav -->
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>

        <div class="offcanvas-info inverse-text">
            <button class="plain offcanvas-close offcanvas-info-close"><i class="jam jam-close"></i></button>
            <a href="<?= base_url() ?>"><img src="#" srcset="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/images/logo-light.png 1x, https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/images/logo-light@2x.png 2x" alt="" /></a>
            <div class="space30"></div>
            <p>Snowlake is a multi-concept and powerful site template contains rich layouts with possibility of unlimited combinations & beautiful elements.</p>
            <div class="space20"></div>
            <div class="widget">
                <h5 class="widget-title">Contact Info</h5>
                <address> Moonshine St. 14/05 <br /> Light City, London <div class="space20"></div>
                    <a href="mailto:first.last@email.com" class="nocolor">info@email.com</a><br /> +00 (123) 456 78 90
                </address>
            </div>
            <!-- /.widget -->
            <div class="widget">
                <h3 class="widget-title">Learn More</h3>
                <ul class="list-unstyled">
                    <li><a href="#" class="nocolor">Our Story</a></li>
                    <li><a href="#" class="nocolor">Terms of Use</a></li>
                    <li><a href="#" class="nocolor">Privacy Policy</a></li>
                    <li><a href="#" class="nocolor">Contact Us</a></li>
                </ul>
            </div>
            <!-- /.widget -->
            <div class="widget">
                <h3 class="widget-title">Follow Us</h3>
                <ul class="social social-mute social-s ml-auto">
                    <li><a href="#"><i class="jam jam-twitter"></i></a></li>
                    <li><a href="#"><i class="jam jam-facebook"></i></a></li>
                    <li><a href="#"><i class="jam jam-instagram"></i></a></li>
                    <li><a href="#"><i class="jam jam-vimeo"></i></a></li>
                    <li><a href="#"><i class="jam jam-youtube"></i></a></li>
                </ul>
            </div>
            <!-- /.widget -->
        </div>

        <!-- Header -->
        <?php $this->load->view('templates/frontend/header') ?>
        <!--  /.Header -->

        <!-- Main Page -->
        <?php $this->load->view($page) ?>
        <!-- /.Main Page -->

        <!-- Footer -->
        <?php $this->load->view('templates/frontend/footer') ?>
        <!-- /.Footer -->
    </div>
    <!-- /.content-wrapper -->
    <script src="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/js/jquery.min.js"></script>
    <script src="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/js/popper.min.js"></script>
    <script src="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/js/bootstrap.min.js"></script>
    <script src="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/revolution/js/jquery.themepunch.tools.min.js"></script>
    <script src="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/revolution/js/jquery.themepunch.revolution.min.js"></script>
    <script src="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/revolution/js/extensions/revolution.extension.actions.min.js"></script>
    <script src="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/revolution/js/extensions/revolution.extension.carousel.min.js"></script>
    <script src="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/revolution/js/extensions/revolution.extension.kenburn.min.js"></script>
    <script src="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
    <script src="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/revolution/js/extensions/revolution.extension.migration.min.js"></script>
    <script src="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
    <script src="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/revolution/js/extensions/revolution.extension.parallax.min.js"></script>
    <script src="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
    <script src="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/revolution/js/extensions/revolution.extension.video.min.js"></script>
    <script src="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/js/plugins.js"></script>
    <script src="https://appt.demoo.id/tema/snowlake/snowlake-html/snowlake/style/js/scripts.js"></script>
</body>

</html>