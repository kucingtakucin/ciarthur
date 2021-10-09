<div class="card">
      <div class="card-header">
            <h1><?php echo lang('edit_user_heading'); ?></h1>
            <p><?php echo lang('edit_user_subheading'); ?></p>
      </div>

      <div class="card-body">
            <form id="update_user" class="needs-validation" onsubmit="$update_user(event,'<?= $user->id ?>')" novalidate>

                  <div class="form-group">
                        <?php echo lang('edit_user_fname_label', 'first_name'); ?> <br />
                        <?php echo form_input($first_name, '', 'class="form-control" required autocomplete="off" required autocomplete="off"'); ?>
                        <?= validation_feedback("First name", "wajib diisi") ?>
                  </div>

                  <div class="form-group">
                        <?php echo lang('edit_user_lname_label', 'last_name'); ?> <br />
                        <?php echo form_input($last_name, '', 'class="form-control" required autocomplete="off"'); ?>
                        <?= validation_feedback("Last name", "wajib diisi") ?>
                  </div>

                  <div class="form-group">
                        <?php echo lang('edit_user_company_label', 'company'); ?> <br />
                        <?php echo form_input($company, '', 'class="form-control" required autocomplete="off"'); ?>
                        <?= validation_feedback("Company", "wajib diisi") ?>
                  </div>

                  <div class="form-group">
                        <?php echo lang('edit_user_phone_label', 'phone'); ?> <br />
                        <?php echo form_input($phone, '', 'class="form-control" required autocomplete="off"'); ?>
                        <?= validation_feedback("Phone", "wajib diisi") ?>
                  </div>

                  <div class="form-group">
                        <?php echo lang('edit_user_password_label', 'password'); ?> <br />
                        <?php echo form_input($password, '', 'class="form-control" autocomplete="off" minlength="8"'); ?>
                        <?= validation_feedback("Password", "wajib diisi & minimal 8 karakter") ?>
                  </div>

                  <div class="form-group">
                        <?php echo lang('edit_user_password_confirm_label', 'password_confirm'); ?><br />
                        <?php echo form_input($password_confirm, '', 'class="form-control" autocomplete="off" minlength="8"'); ?>
                        <?= validation_feedback("Password Confirmation", "wajib diisi & minimal 8 karakter") ?>
                  </div>

                  <?php if ($this->ion_auth->is_admin()) : ?>

                        <h3><?php echo lang('edit_user_groups_heading'); ?></h3>
                        <div class="form-check form-check-inline">
                              <?php foreach ($groups as $group) : ?>
                                    <input type="radio" name="groups[]" class="form-check-input" value="<?= $group['id']; ?>" <?= (in_array($group, $currentGroups)) ? 'checked="checked"' : null; ?>>
                                    <label class="checkbox form-check-label mr-3">
                                          <?php echo htmlspecialchars($group['name'], ENT_QUOTES, 'UTF-8'); ?>
                                    </label>
                              <?php endforeach ?>
                        </div>

                  <?php endif ?>

                  <?php echo form_hidden('id', $user->id); ?>

                  <div class="form-group mt-3">
                        <a href="<?= base_auth('users') ?>" class="btn btn-secondary btn-pill">Kembali</a>
                        <?php echo form_submit('submit', lang('edit_user_submit_btn'), 'class="btn btn-primary btn-pill"'); ?>
                        <button class="btn btn-primary btn-pill loader" type="button" disabled style="display: none;">
                              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                              Loading...
                        </button>
                  </div>

            </form>
      </div>
</div>