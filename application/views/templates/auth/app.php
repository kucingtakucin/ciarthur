<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= sistem()->nama ?> | <?= @$title ?></title>

    <!-- Meta description, keywords, author, icon -->
    <meta name="description" content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="<?= config_item('assets_auth') ?>images/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="<?= config_item('assets_auth') ?>images/favicon.png" type="image/x-icon">

    <!-- Google re-Captcha  -->
    <?= recaptcha_render_js() ?>

    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="<?= config_item('assets_auth') ?>css/fontawesome.css">

    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="<?= config_item('assets_auth') ?>css/vendors/icofont.css">

    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="<?= config_item('assets_auth') ?>css/vendors/themify.css">

    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="<?= config_item('assets_auth') ?>css/vendors/flag-icon.css">

    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="<?= config_item('assets_auth') ?>css/vendors/feather-icon.css">

    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="<?= config_item('assets_auth') ?>css/vendors/bootstrap.css">

    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="<?= config_item('assets_auth') ?>css/style.css">
    <link id="color" rel="stylesheet" href="<?= config_item('assets_auth') ?>css/color-1.css" media="screen">

    <!-- Pace -->
    <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@latest/pace-theme-default.min.css">

    <!-- OfflineJs -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/custom/offlinejs/css/offline.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/custom/offlinejs/css/offline-language-english.css">

    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="<?= config_item('assets_auth') ?>css/responsive.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css">

</head>

<body>
    <div id="firebaseui-auth-container"></div>
    <div class="preloader-container">
        <svg class="preloader" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 340 340">
            <circle cx="170" cy="170" r="160" stroke="#E2007C" />
            <circle cx="170" cy="170" r="135" stroke="#404041" />
            <circle cx="170" cy="170" r="110" stroke="#E2007C" />
            <circle cx="170" cy="170" r="85" stroke="#404041" />
        </svg>
    </div>

    <!-- Login Page -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-7"><img class="bg-img-cover bg-center" src="<?= config_item('assets_auth') ?>images/login/2.jpg" alt="looginpage">
            </div>
            <div class="col-xl-5 p-0">
                <?= $this->load->view($page, '', true) ?>
            </div>
        </div>
    </div>

    <!-- Axios -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Bootstrap js-->
    <script src="<?= config_item('assets_auth') ?>js/bootstrap/popper.min.js"></script>
    <script src="<?= config_item('assets_auth') ?>js/bootstrap/bootstrap.js"></script>

    <!-- feather icon js-->
    <script src="<?= config_item('assets_auth') ?>js/icons/feather-icon/feather.min.js"></script>
    <script src="<?= config_item('assets_auth') ?>js/icons/feather-icon/feather-icon.js"></script>

    <!-- Sidebar jquery-->
    <script src="<?= config_item('assets_auth') ?>js/config.js"></script>

    <!-- Sweet Alert 2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Form Validation Custom  -->
    <script src="<?= config_item('assets_auth') ?>js/form-validation-custom.js"></script>

    <!-- Cookie js -->
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js" integrity="sha256-0H3Nuz3aug3afVbUlsu12Puxva3CP4EhJtPExqs54Vg=" crossorigin="anonymous"></script>

    <!-- SocketIO  -->
    <script src="https://cdn.socket.io/4.1.2/socket.io.min.js" integrity="sha384-toS6mmwu70G0fw54EGlWWeA4z3dyJ+dlXBtSURSKN4vyRFOcxd3Bzjj/AoOwY+Rg" crossorigin="anonymous"></script>

    <!-- Theme js-->
    <script src="<?= config_item('assets_auth') ?>js/script.js"></script>

    <!-- Loading Overlay -->
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Offline Js -->
    <script src="<?= base_url() ?>assets/custom/offlinejs/js/offline.min.js"></script>

    <!-- CryptoJs AES -->
    <script src="<?= base_url() ?>assets/custom/cryptojs-aes/cryptojs-aes.min.js"></script>
    <script src="<?= base_url() ?>assets/custom/cryptojs-aes/cryptojs-aes-format.js"></script>

    <!-- Custom Javascripts -->
    <script>
        // ================================================//
        /**
         * Implement hex2bin and bin2hex in JavaScript
         * https://gist.github.com/jasperck
         *
         * Copyright 2017, JasperChang <jasperc8@gmail.com>
         * Licensed under The MIT License
         * http://www.opensource.org/licenses/mit-license
         */

        const _hex2bin = str => str.match(/.{1,2}/g).reduce((str, hex) => str += String.fromCharCode(parseInt(hex, 16)), '');

        const _bin2hex = str => str.split('').reduce((str, glyph) => str += glyph.charCodeAt().toString(16).length < 2 ? `0${glyph.charCodeAt().toString(16)}` :
            glyph.charCodeAt().toString(16), '');

        // Crypto.Js AES encrypt
        const _cryptoAesJson_encrypt = (valueToEncrypt) => {
            let password = "<?= config_item('cryptojs_aes_password') ?>";
            let encrypted = CryptoJSAesJson.encrypt(valueToEncrypt, password)
            return encrypted
        }

        // Crypto.Js AES decrypt
        const _cryptoAesJson_decrypt = (valueToDecrypt) => {
            let password = "<?= config_item('cryptojs_aes_password') ?>"
            let decrypted = CryptoJSAesJson.decrypt(valueToDecrypt, password)
            return decrypted
        }

        /**
         * Keperluan generate csrf
         */
        // ================================================== //

        // JQuery AJAX Interceptor 
        $.ajaxSetup({
            beforeSend: function(xhr, settings) {
                xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest")

                xhr.setRequestHeader("X-CI-XSRF-TOKEN", _cryptoAesJson_encrypt({
                    '<?= bin2hex('token_name') ?>': '<?= base64_encode($this->security->get_csrf_token_name()) ?>',
                    '<?= bin2hex('hash') ?>': Cookies.get(atob('<?= base64_encode(config_item('cookie_prefix') . config_item('csrf_cookie_name')) ?>'))
                }))

                if (settings.data instanceof FormData) {
                    for ([k, v] of settings.data.entries()) {
                        settings.data.set(k, _cryptoAesJson_encrypt(v))
                    }
                } else if (typeof settings.data === 'string') {
                    const urlParams = new URLSearchParams(settings.data);
                    const entries = urlParams.entries(); //returns an iterator of decoded [key,value] tuples
                    const obj = {}
                    for (const [key, value] of entries) { // each 'entry' is a [key, value] tupple
                        obj[key] = _cryptoAesJson_encrypt(value);
                    }
                    settings.data = new URLSearchParams(obj).toString()
                }
            }
        });

        $(document).ajaxSuccess(function(event, jqxhr, settings) {});

        $(document).ajaxError(function(event, jqxhr, settings) {
            $.LoadingOverlay("hide")
        });

        $(document).ajaxStart((event, jqxhr, settings) => $.LoadingOverlay("show"));
        $(document).ajaxStop((event, jqxhr, settings) => $.LoadingOverlay("hide"));

        /** Set default AJAX headers */
        axios.defaults.headers.common = {
            "X-Requested-With": "XMLHttpRequest",
        };

        // Add a request interceptor
        axios.interceptors.request.use(function(config) {
            $.LoadingOverlay("show")
            // Do something before request is sent
            if (config?.headers) config.headers['X-CI-XSRF-TOKEN'] = _cryptoAesJson_encrypt({
                '<?= bin2hex('token_name') ?>': '<?= base64_encode($this->security->get_csrf_token_name()) ?>',
                '<?= bin2hex('hash') ?>': Cookies.get(atob('<?= base64_encode(config_item('cookie_prefix') . config_item('csrf_cookie_name')) ?>'))
            });

            if (config?.data && config?.data instanceof FormData) {
                for ([k, v] of config?.data.entries()) {
                    config?.data.set(k, _cryptoAesJson_encrypt(v))
                }
            }

            return config;
        }, function(error) {
            $.LoadingOverlay("hide")
            // Do something with request error
            return Promise.reject(error);
        });

        // Add a response interceptor
        axios.interceptors.response.use(function(response) {
            $.LoadingOverlay("hide")
            // Any status code that lie within the range of 2xx cause this function to trigger
            // Do something with response data
            return response;
        }, function(error) {
            $.LoadingOverlay("hide")
            // Any status codes that falls outside the range of 2xx cause this function to trigger
            // Do something with response error
            return Promise.reject(error);
        });

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
    </script>

    <?= $this->load->view($script, '', true) ?>
</body>

</html>