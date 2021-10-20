<div class="login-card">
    <div>
        <div><a class="logo text-left" href="<?= base_url() ?>"><img class="img-fluid for-light" src="<?= base_url() ?>assets/cuba/images/logo/login.png" alt="looginpage"><img class="img-fluid for-dark" src="<?= base_url() ?>assets/cuba/images/logo/logo_dark.png" alt="looginpage"></a></div>
        <div class="login-main">

            <form class="theme-form" id="form-login" method="post" action="<?= base_url('auth/login') ?>">
                <h4>Login</h4>
                <p>Enter your username & password to login</p>
                <div class="form-group">
                    <label class="col-form-label" for="identity">Username</label>
                    <input class="form-control" id="input_login_identity" name="identity" type="text">
                    <?= validation_feedback('login', 'identity') ?>
                </div>
                <div class="form-group">
                    <label class="col-form-label" for="password">Password</label>
                    <input class="form-control" type="password" id="input_login_password" name="password">
                    <div class="show-hide"><span class="show"> </span></div>
                    <?= validation_feedback('login', 'password') ?>
                </div>
                <div class="form-group">
                    <?= recaptcha_display() ?>
                </div>
                <div class="form-group mb-0">
                    <div class="checkbox p-0">
                        <input id="remember" type="checkbox" name="remember">
                        <label class="text-muted" for="remember">Remember me</label>
                    </div>
                    <button class="btn btn-primary btn-block" type="submit">Sign in</button>
                </div>
                <!-- <h6 class="text-muted mt-4 or">Or Sign in with</h6>
                <div class="social mt-4">
                    <div class="btn-showcase"><a class="btn btn-light" href="https://www.linkedin.com/login" target="_blank"><i class="txt-linkedin" data-feather="linkedin"></i> LinkedIn </a><a class="btn btn-light" href="https://twitter.com/login?lang=en" target="_blank"><i class="txt-twitter" data-feather="twitter"></i>twitter</a><a class="btn btn-light" href="https://www.facebook.com/" target="_blank"><i class="txt-fb" data-feather="facebook"></i>facebook</a></div>
                </div>
                <p class="mt-4 mb-0">Don't have account?<a class="ml-2" href="sign-up.html">Create
                        Account</a></p> -->
            </form>
        </div>
    </div>
</div>