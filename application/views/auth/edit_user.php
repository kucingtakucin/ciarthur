<div class="card">
      <div class="card-header">
            <h1><?php echo lang('edit_user_heading'); ?></h1>
            <p><?php echo lang('edit_user_subheading'); ?></p>
      </div>

      <div class="card-body">
            <div id="infoMessage" class="text-danger mb-3"><?php echo $message; ?></div>

            <?php echo form_open(uri_string()); ?>

            <div class="form-group">
                  <?php echo lang('edit_user_fname_label', 'first_name'); ?> <br />
                  <?php echo form_input($first_name, '', 'class="form-control"'); ?>
            </div>

            <div class="form-group">
                  <?php echo lang('edit_user_lname_label', 'last_name'); ?> <br />
                  <?php echo form_input($last_name, '', 'class="form-control"'); ?>
            </div>

            <div class="form-group">
                  <?php echo lang('edit_user_company_label', 'company'); ?> <br />
                  <?php echo form_input($company, '', 'class="form-control"'); ?>
            </div>

            <div class="form-group">
                  <?php echo lang('edit_user_phone_label', 'phone'); ?> <br />
                  <?php echo form_input($phone, '', 'class="form-control"'); ?>
            </div>

            <div class="form-group">
                  <?php echo lang('edit_user_password_label', 'password'); ?> <br />
                  <?php echo form_input($password, '', 'class="form-control"'); ?>
            </div>

            <div class="form-group">
                  <?php echo lang('edit_user_password_confirm_label', 'password_confirm'); ?><br />
                  <?php echo form_input($password_confirm, '', 'class="form-control"'); ?>
            </div>

            <?php if ($this->ion_auth->is_admin()) : ?>

                  <h3><?php echo lang('edit_user_groups_heading'); ?></h3>
                  <div class="form-check form-check-inline">
                        <?php foreach ($groups as $group) : ?>
                              <input type="checkbox" name="groups[]" class="form-check-input" value="<?php echo $group['id']; ?>" <?php echo (in_array($group, $currentGroups)) ? 'checked="checked"' : null; ?>>
                              <label class="checkbox form-check-label mr-3">
                                    <?php echo htmlspecialchars($group['name'], ENT_QUOTES, 'UTF-8'); ?>
                              </label>
                        <?php endforeach ?>
                  </div>

            <?php endif ?>

            <?php echo form_hidden('id', $user->id); ?>
            <?php echo form_hidden($csrf); ?>

            <div class="form-group mt-3">
                  <a href="<?= base_auth() ?>" class="btn btn-secondary btn-pill">Kembali</a>
                  <?php echo form_submit('submit', lang('edit_user_submit_btn'), 'class="btn btn-primary btn-pill"'); ?>
            </div>

            <?php echo form_close(); ?>
      </div>
</div>