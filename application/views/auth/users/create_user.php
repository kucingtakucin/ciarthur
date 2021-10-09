<div class="card">
    <div class="card-header">
        <h1><?php echo lang('create_user_heading'); ?></h1>
        <p><?php echo lang('create_user_subheading'); ?></p>
    </div>
    <div class="card-body">
        <form id="create_user" class="needs-validation" onsubmit="$create_user(event)" novalidate>
            <div class="form-group">
                <?php echo lang('create_user_fname_label', 'first_name'); ?>
                <?php echo form_input($first_name, "", 'class="form-control" required autocomplete="off"'); ?>
                <?= validation_feedback("First name", "wajib diisi") ?>
            </div>

            <div class="form-group">
                <?php echo lang('create_user_lname_label', 'last_name'); ?> <br />
                <?php echo form_input($last_name, "", 'class="form-control" required autocomplete="off"'); ?>
                <?= validation_feedback("Last name", "wajib diisi") ?>
            </div>

            <?php
            if ($identity_column !== 'email') {
                echo '<div class="form-group">';
                echo lang('create_user_identity_label', 'identity');
                echo form_error('identity');
                echo form_input($identity, "", 'class="form-control" required autocomplete="off"');
                echo validation_feedback("Identity", "wajib diisi");
                echo '</div>';
            }
            ?>

            <div class="form-group">
                <?php echo lang('create_user_company_label', 'company'); ?> <br />
                <?php echo form_input($company, "", 'class="form-control" required autocomplete="off"'); ?>
                <?= validation_feedback("Company", "wajib diisi") ?>
            </div>

            <div class="form-group">
                <?php echo lang('create_user_email_label', 'email'); ?> <br />
                <?php echo form_input($email, "", 'class="form-control" required autocomplete="off"'); ?>
                <?= validation_feedback("Email", "wajib diisi") ?>
            </div>

            <div class="form-group">
                <?php echo lang('create_user_phone_label', 'phone'); ?> <br />
                <?php echo form_input($phone, "", 'class="form-control" required autocomplete="off"'); ?>
                <?= validation_feedback("No Phone", "wajib diisi") ?>
            </div>

            <div class="form-group">
                <?php echo lang('create_user_password_label', 'password'); ?> <br />
                <?php echo form_input($password, "", 'class="form-control" minlength="8" required autocomplete="off"'); ?>
                <?= validation_feedback("Password", "wajib diisi dan minimal 8 karakter") ?>
            </div>

            <div class="form-group">
                <?php echo lang('create_user_password_confirm_label', 'password_confirm'); ?> <br />
                <?php echo form_input($password_confirm, "", 'class="form-control" minlength="8" required autocomplete="off"'); ?>
                <?= validation_feedback("Password Confirmation", "wajib diisi dan minimal 8 karakter") ?>
            </div>


            <div class="form-group">
                <a href="<?= base_auth('users') ?>" class="btn btn-secondary btn-pill">Kembali</a>
                <?php echo form_submit('submit', lang('create_user_submit_btn'), 'class="btn btn-primary btn-pill"'); ?>
                <button class="btn btn-primary btn-pill loader" type="button" disabled style="display: none;">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
            </div>
        </form>
    </div>
</div>