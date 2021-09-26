<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="<?= base_url() ?>/assets/cuba/images/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="<?= base_url() ?>/assets/cuba/images/favicon.png" type="image/x-icon">
    <title><?= sistem()->nama ?> | <?= $title ?></title>
    <!-- Google re-Captcha  -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/cuba/css/fontawesome.css">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/cuba/css/vendors/icofont.css">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/cuba/css/vendors/themify.css">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/cuba/css/vendors/flag-icon.css">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/cuba/css/vendors/feather-icon.css">
    <!-- Plugins css start-->
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/cuba/css/vendors/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/cuba/css/style.css">
    <link id="color" rel="stylesheet" href="<?= base_url() ?>/assets/cuba/css/color-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/cuba/css/responsive.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css">
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
    <!-- login page start-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-7"><img class="bg-img-cover bg-center" src="<?= base_url() ?>/assets/cuba/images/login/2.jpg" alt="looginpage">
            </div>
            <div class="col-xl-5 p-0">
                <?= $this->load->view($page, '', true) ?>
            </div>
        </div>
        <!-- Axios -->
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

        <!-- latest jquery-->
        <script src="<?= base_url() ?>/assets/cuba/js/jquery-3.5.1.min.js"></script>
        <!-- Bootstrap js-->
        <script src="<?= base_url() ?>/assets/cuba/js/bootstrap/popper.min.js"></script>
        <script src="<?= base_url() ?>/assets/cuba/js/bootstrap/bootstrap.js"></script>
        <!-- feather icon js-->
        <script src="<?= base_url() ?>/assets/cuba/js/icons/feather-icon/feather.min.js"></script>
        <script src="<?= base_url() ?>/assets/cuba/js/icons/feather-icon/feather-icon.js"></script>
        <!-- Sidebar jquery-->
        <script src="<?= base_url() ?>/assets/cuba/js/config.js"></script>
        <!-- Plugins JS start-->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://appt.demoo.id/tema/cuba/html/assets/js/form-validation-custom.js"></script>
        <!-- Cookie js -->
        <script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js" integrity="sha256-0H3Nuz3aug3afVbUlsu12Puxva3CP4EhJtPExqs54Vg=" crossorigin="anonymous"></script>
        <!-- SocketIO  -->
        <script src="https://cdn.socket.io/4.1.2/socket.io.min.js" integrity="sha384-toS6mmwu70G0fw54EGlWWeA4z3dyJ+dlXBtSURSKN4vyRFOcxd3Bzjj/AoOwY+Rg" crossorigin="anonymous"></script>

        <!-- Plugins JS Ends-->
        <!-- Theme js-->
        <script src="<?= base_url() ?>/assets/cuba/js/script.js"></script>
        <!-- login js-->
        <script>
            let csrf, loading;

            /**
             * Keperluan socket.io
             */
            // ================================================== //
            socket = io("ws://localhost:2021")

            csrf = () => {
                socket.emit('minta-csrf', {
                    token: '<?= $this->encryption->encrypt(bin2hex('csrf')) ?>',
                    url: "<?= base_url('csrf/generate') ?>",
                    cookie: Cookies.get('ci_csrf_cookie'),
                    session: Cookies.get('ci_session')
                })

                return new Promise((resolve, reject) => {
                    socket.on('terima-csrf', data => {
                        resolve({
                            token_name: data.csrf_token_name,
                            hash: data.csrf_hash,
                        })
                    })
                })
            }

            $(document).ready(function() {

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
            })
        </script>
        <!-- Plugin used-->
        <?= $this->load->view($script, '', true) ?>
    </div>
</body>

</html>