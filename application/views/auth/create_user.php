<div class="card">
      <div class="card-header">
            <h1><?php echo lang('create_user_heading'); ?></h1>
            <p><?php echo lang('create_user_subheading'); ?></p>
      </div>
      <div class="card-body">
            <div id="infoMessage" class="text-danger mb-3"><?php echo $message; ?></div>

            <?php echo form_open("auth/create_user"); ?>

            <div class="form-group">
                  <?php echo lang('create_user_fname_label', 'first_name'); ?>
                  <?php echo form_input($first_name, "", 'class="form-control"'); ?>
            </div>

            <div class="form-group">
                  <?php echo lang('create_user_lname_label', 'last_name'); ?> <br />
                  <?php echo form_input($last_name, "", 'class="form-control"'); ?>
            </div>

            <?php
            if ($identity_column !== 'email') {
                  echo '<div class="form-group">';
                  echo lang('create_user_identity_label', 'identity');
                  echo form_error('identity');
                  echo form_input($identity, "", 'class="form-control"');
                  echo '</div>';
            }
            ?>

            <div class="form-group">
                  <?php echo lang('create_user_company_label', 'company'); ?> <br />
                  <?php echo form_input($company, "", 'class="form-control"'); ?>
            </div>

            <div class="form-group">
                  <?php echo lang('create_user_email_label', 'email'); ?> <br />
                  <?php echo form_input($email, "", 'class="form-control"'); ?>
            </div>

            <div class="form-group">
                  <?php echo lang('create_user_phone_label', 'phone'); ?> <br />
                  <?php echo form_input($phone, "", 'class="form-control"'); ?>
            </div>

            <div class="form-group">
                  <?php echo lang('create_user_password_label', 'password'); ?> <br />
                  <?php echo form_input($password, "", 'class="form-control"'); ?>
            </div>

            <div class="form-group">
                  <?php echo lang('create_user_password_confirm_label', 'password_confirm'); ?> <br />
                  <?php echo form_input($password_confirm, "", 'class="form-control"'); ?>
            </div>


            <div class="form-group">
                  <a href="<?= base_auth() ?>" class="btn btn-secondary btn-pill">Kembali</a>
                  <?php echo form_submit('submit', lang('create_user_submit_btn'), 'class="btn btn-primary btn-pill"'); ?>
            </div>

            <?php echo form_close(); ?>
      </div>
</div>