<div class="card">
    <div class="card-header">
        <h1>Create Role</h1>
        <p>Please enter the role information below.</p>
    </div>

    <div class="card-body">

        <form id="create_role" class="needs-validation" onsubmit="$create_role(event)" novalidate>

            <div class="form-group">
                <label for="role_name">Role Name</label>
                <?php echo form_input($group_name, "", 'class="form-control" id="role_name" required autocomplete="off"'); ?>
                <?= validation_feedback("Role name", "wajib diisi") ?>
            </div>

            <div class="form-group">
                <label for="role_description">Role Description</label>
                <?php echo form_input($description, "", 'class="form-control" id="role_description" required autocomplete="off"'); ?>
                <?= validation_feedback("Role description", "wajib diisi") ?>
            </div>

            <div class="form-group">
                <a href="<?= base_auth('roles') ?>" class="btn btn-secondary btn-pill">Kembali</a>
                <?php echo form_submit('submit', 'Create Role', 'class="btn btn-primary btn-pill"'); ?>
                <button class="btn btn-primary btn-pill loader" type="button" disabled style="display: none;">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
            </div>

        </form>
    </div>
</div>