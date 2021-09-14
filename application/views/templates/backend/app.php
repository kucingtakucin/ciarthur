<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= sistem()->nama ?> | <?= $title ?></title>

    <!-- Meta description, keywords, author, icon -->
    <meta name="description" content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="https://appt.demoo.id/tema/cuba/html/assets/images/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="https://appt.demoo.id/tema/cuba/html/assets/images/favicon.png" type="image/x-icon">

    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="https://appt.demoo.id/tema/cuba/html/assets/css/fontawesome.css">

    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="https://appt.demoo.id/tema/cuba/html/assets/css/vendors/icofont.css">

    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="https://appt.demoo.id/tema/cuba/html/assets/css/vendors/themify.css">

    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="https://appt.demoo.id/tema/cuba/html/assets/css/vendors/flag-icon.css">

    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="https://appt.demoo.id/tema/cuba/html/assets/css/vendors/feather-icon.css">

    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="https://appt.demoo.id/tema/cuba/html/assets/css/vendors/bootstrap.css">

    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="https://appt.demoo.id/tema/cuba/html/assets/css/style.css">
    <link id="color" rel="stylesheet" href="https://appt.demoo.id/tema/cuba/html/assets/css/color-1.css" media="screen">

    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="https://appt.demoo.id/tema/cuba/html/assets/css/responsive.css">

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <!-- Animate.css -->
    <link rel="stylesheet" type="text/css" href="https://appt.demoo.id/tema/cuba/html/assets/css/vendors/animate.css">

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.11.0/b-2.0.0/b-colvis-2.0.0/b-html5-2.0.0/b-print-2.0.0/fh-3.1.9/r-2.2.9/datatables.min.css" />

    <!-- Bootstrap Datepicker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />

    <!-- Custom Stylesheets -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/cuba/css/style.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css">
    <?= $this->load->view($style, '', true) ?>
</head>

<body>
    <div class="preloader-container">
        <svg class="preloader" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 340 340">
            <circle cx="170" cy="170" r="160" stroke="#E2007C" />
            <circle cx="170" cy="170" r="135" stroke="#404041" />
            <circle cx="170" cy="170" r="110" stroke="#E2007C" />
            <circle cx="170" cy="170" r="85" stroke="#404041" />
        </svg>
    </div>

    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <!-- Page Header Start-->
        <div class="page-header">
            <div class="header-wrapper row m-0">
                <div class="header-logo-wrapper col-1">
                    <div class="logo-wrapper">
                        <a href="<?= base_url() ?>">
                            <img class="img-fluid" src="https://appt.demoo.id/tema/cuba/html/assets/images/logo/logo.png" alt="">
                        </a>
                    </div>
                    <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="grid" id="sidebar-toggle"> </i></div>
                </div>
                <div class="nav-right col-11 pull-right right-header p-0">
                    <ul class="nav-menus">
                        <li>
                            <div class="mode"><i class="fa fa-moon-o"></i></div>
                        </li>
                        <li class="maximize"><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>
                        <li class="profile-nav onhover-dropdown p-0 mr-0">
                            <div class="media profile-media"><img class="b-r-10" src="https://appt.demoo.id/tema/cuba/html/assets/images/dashboard/profile.jpg" alt="">
                                <div class="media-body"><span><?= ucwords($this->ion_auth_model->user()->row()->username) ?></span>
                                    <p class="mb-0 font-roboto"><?= ($this->ion_auth_model->in_group("admin") ? 'Admin' : 'Member') ?> <i class="middle fa fa-angle-down"></i></p>
                                </div>
                            </div>
                            <ul class="profile-dropdown onhover-show-div">
                                <li id="edit-account"><i data-feather="user"></i><span>Account </span></li>
                                <li id="logout"><i data-feather="log-out"></i><span>Log out</span></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Page Header Ends -->
        <!-- Page Body Start-->
        <div class="page-body-wrapper horizontal-menu">
            <!-- Page Sidebar Start-->
            <div class="sidebar-wrapper">
                <div class="logo-wrapper">
                    <a href="<?= base_url() ?>">
                        <img class="img-fluid for-light" src="https://appt.demoo.id/tema/cuba/html/assets/images/logo/logo.png" alt="logo">
                        <img class="img-fluid for-dark" src="https://appt.demoo.id/tema/cuba/html/assets/images/logo/logo_dark.png" alt="logo dark">
                    </a>
                </div>
                <div class="logo-icon-wrapper"><a href="<?= base_url() ?>"><img class="img-fluid" src="https://appt.demoo.id/tema/cuba/html/assets/images/logo/logo-icon.png" alt=""></a></div>
                <nav>
                    <div class="sidebar-main">
                        <div id="sidebar-menu">
                            <ul class="sidebar-links custom-scrollbar">
                                <?= $this->load->view('templates/backend/sidebar', '', true) ?>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
            <!-- Page Sidebar Ends-->
            <div class="page-body">
                <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-lg-12">
                                <h3><?= $title ?></h3>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
                                    <li class="breadcrumb-item"><?= $title ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <div class="row starter-main">
                        <div class="col-sm-12">
                            <?= $this->load->view($page, '', true) ?>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid Ends-->
            </div>

            <!-- Modals -->
            <?php foreach ($modals as $modal) : ?>
                <?= $this->load->view($modal, '', true) ?>
            <?php endforeach ?>

            <div class="modal fade" id="modal_account" role="dialog" aria-labelledby="modal-popin" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form class="needs-validation" id="form_account" method="post" enctype="multipart/form-data" novalidate>
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Account</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-12" for="username">Username</label>
                                            <input type="text" id="ubah_username" class="form-control" name="username" value="<?= user()->username ?>" readonly required autocomplete="off" placeholder="Masukkan Username">
                                            <?= validation_feedback("username", "wajib diisi") ?>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-12" for="password">Password</label>
                                            <input type="password" id="ubah_password" class="form-control" name="password" minlength="8" required autocomplete="off" placeholder="Masukkan Password">
                                            <?= validation_feedback("password", "wajib diisi dan minimal 8 karakter") ?>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="id" id="id" value="<?= user()->id ?>">
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal" data-original-title="" title="">Close</button>
                                <button class="btn btn-primary" type="submit" data-original-title="" title="">Submit Data</button>
                                <button class="btn btn-primary loader" type="button" disabled style="display: none;">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Loading...
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- END Pop In Modal -->

            <?= $this->load->view('templates/backend/footer', '', true) ?>
        </div>
    </div>
    <!-- latest jquery-->
    <!-- <script src="https://appt.demoo.id/tema/cuba/html/assets/js/jquery-3.5.1.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Bootstrap js-->
    <script src="https://appt.demoo.id/tema/cuba/html/assets/js/bootstrap/popper.min.js"></script>
    <script src="https://appt.demoo.id/tema/cuba/html/assets/js/bootstrap/bootstrap.js"></script>

    <!-- feather icon js-->
    <script src="https://appt.demoo.id/tema/cuba/html/assets/js/icons/feather-icon/feather.min.js"></script>
    <script src="https://appt.demoo.id/tema/cuba/html/assets/js/icons/feather-icon/feather-icon.js"></script>

    <!-- Sidebar jquery-->
    <script src="<?= base_url() ?>assets/cuba/js/sidebar-menu.js"></script>
    <script src="https://appt.demoo.id/tema/cuba/html/assets/js/config.js"></script>

    <!-- Clipboard .js-->
    <script src="https://appt.demoo.id/tema/cuba/html/assets/js/clipboard/clipboard.min.js"></script>

    <!-- Custom Card -->
    <script src="https://appt.demoo.id/tema/cuba/html/assets/js/custom-card/custom-card.js"></script>

    <!-- Tooltip init -->
    <script src="https://appt.demoo.id/tema/cuba/html/assets/js/tooltip-init.js"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- DataTables -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.11.0/b-2.0.0/b-colvis-2.0.0/b-html5-2.0.0/b-print-2.0.0/fh-3.1.9/r-2.2.9/datatables.min.js">
    </script>

    <!-- JQuery Validation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

    <!-- Bootstrap Datepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- SweetAlert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Leaflet.js -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

    <!-- Form Validation Custom -->
    <script src="https://appt.demoo.id/tema/cuba/html/assets/js/form-validation-custom.js"></script>

    <!-- Bootstrap Custom File Input -->
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>

    <!-- Axios -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <!-- Apex Charts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Pusher -->
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    
    <!-- Cookie js -->
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js" integrity="sha256-0H3Nuz3aug3afVbUlsu12Puxva3CP4EhJtPExqs54Vg=" crossorigin="anonymous"></script>

    <!-- Custom Scripts-->
    <script src="<?= base_url() ?>assets/cuba/js/script.js"></script>
    <script>
        let csrf, loading, $edit_account;
        $(document).ready(function() {

            /**
            * Keperluan pusher pengaduan
            */
            // ================================================== //
            Pusher.logToConsole = true;
            
            let pusher = new Pusher('21a14c9bc94b57c3db03', {
                cluster: 'ap1'
            });

            let channel = pusher.subscribe('kirim-pengaduan-channel');
            channel.bind('kirim-pengaduan-event', function(data) {
                Swal.fire({
                    title: data.title, 
                    icon: 'info', 
                    text: data.message
                })
            });

            /**
            * Keperluan show preloader
            */
            // ================================================== //
            $('.preloader-container').fadeOut(500)

            /**
            * Keperluan resize Google Recaptchaa
            */
            // ================================================== //
            
            let width = $('.g-recaptcha').parent().width();
            if (width < 302) {
                let scale = width / 302;
                $('.g-recaptcha').css('transform', 'scale(' + scale + ')');
                $('.g-recaptcha').css('-webkit-transform', 'scale(' + scale + ')');
                $('.g-recaptcha').css('transform-origin', '0 0');
                $('.g-recaptcha').css('-webkit-transform-origin', '0 0');
            }

            /**
            * Keperluan disable inspect element
            */
            // ================================================== //
            
            // Disable right click
            $(document).contextmenu(function(event) {
                event.preventDefault()
            })

            $(document).keydown(function(event) {
                // Disable F12
                if (event.keyCode == 123) return false;

                // Disable Ctrl + Shift + I
                if (event.ctrlKey && event.shiftKey && event.keyCode == 'I'.charCodeAt(0)) {
                    return false;
                }

                // Disable Ctrl + Shift + J
                if (event.ctrlKey && event.shiftKey && event.keyCode == 'J'.charCodeAt(0)) {
                    return false;
                }

                // Disable Ctrl + U
                if (event.ctrlKey && event.keyCode == 'U'.charCodeAt(0)) {
                    return false;
                }
            })

            /**
            * Keperluan show loading
            */
            // ================================================== //
            loading = () => {
                Swal.fire({
                    title: 'Loading...',
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                })
            }

            /**
            * Keperluan generate csrf
            */
            // ================================================== //
            csrf = async () => {
                let formData = new FormData()
                formData.append('key', '<?= $this->encryption->encrypt(bin2hex('csrf')) ?>')

                let res = await axios.post("<?= base_url('csrf/generate') ?>", formData)
                return {
                    token_name: res.data.csrf_token_name,
                    hash: res.data.csrf_hash
                }
            }

            /**
            * Keperluan edit account
            */
            // ================================================== //
            $('#edit-account').click(function () {
                $('#modal_account').modal('show')
            })

            $('#form_account').submit(function(event) {
                event.preventDefault();
                if (this.checkValidity()) {
                    $edit_account(this);
                }
            });

             $('#modal_account').on('hide.bs.modal', () => {
                $('#form_account').removeClass('was-validated')
                $('#form_account').trigger('reset')
            })

            $edit_account = async (form) => {
                let formData = new FormData(form)
                formData.append(
                        await csrf().then(csrf => csrf.token_name),
                        await csrf().then(csrf => csrf.hash)
                )
                axios.post("<?= base_url('auth/edit_account') ?>", formData)
                    .then(res => {

                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: res.data.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }).catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: err.response.data.message,
                            // text: err.response.statusText,
                        })
                    }).then(() => {
                        $('#form_account button[type=submit]').show();
                        $('#form_account button.loader').hide();
                        $('#form_account').trigger('reset');
                        $('#form_account').removeClass('was-validated')
                        $('#modal_account').modal('hide');
                    })
            }

            $('#logout').click(function () {
                location.replace("<?= base_url('logout') ?>")
            })
        })
    </script>
    <?= $this->load->view($script, '', true) ?>
</body>

</html>