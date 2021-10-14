<div class="card">
      <div class="card-header">
            <h1>Edit Role</h1>
            <p>Please enter the role information below</p>
      </div>

      <div class="card-body">
            <form id="update_role" class="needs-validation" onsubmit="$update_role(event, '<?= $group_id ?>')" novalidate>

                  <div class="form-group">
                        <label for="role_name">Role Name</label>
                        <?php echo form_input($group_name, '', 'id="role_name" class="form-control" required autocomplete="off"'); ?>
                        <?= validation_feedback("Role name", "wajib diisi") ?>
                  </div>

                  <div class="form-group">
                        <!-- <?php echo lang('edit_group_desc_label', 'description'); ?> -->
                        <label for="role_description">Deskripsi</label>
                        <?php echo form_input($group_description, '', 'id="deskripsi" class="form-control" required autocomplete="off"'); ?>
                        <?= validation_feedback("Deskripsi", "wajib diisi") ?>
                  </div>

                  <div class="form-group">
                        <a href="<?= base_auth('roles') ?>" class="btn btn-secondary btn-pill">Kembali</a>
                        <?php echo form_submit('submit', 'Save Role', 'class="btn btn-primary btn-pill"'); ?>
                        <button class="btn btn-primary btn-pill loader" type="button" disabled style="display: none;">
                              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                              Loading...
                        </button>
                  </div>
            </form>
      </div>
</div>