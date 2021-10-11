<div class="card">
    <div class="card-header">
        <h1>Add Permission</h1>
    </div>
    <div class="card-body">
        <form id="add_permission" class="needs-validation" onsubmit="$add_permission(event)" novalidate>

            <div class="form-group">
                <label for="perm_key">Permission Key</label>
                <?php echo form_input('perm_key', set_value('perm_key'), 'class="form-control" id="perm_key" required autocomplete="off"'); ?>
                <?= validation_feedback("Permission Key", "wajib diisi") ?>
            </div>

            <div class="form-group">
                <label for="perm_name">Permission Name</label>
                <?php echo form_input('perm_name', set_value('perm_name'), 'class="form-control" id="perm_key" required autocomplete="off"'); ?> <br />
                <?= validation_feedback("Permission Name", "wajib diisi") ?>
            </div>

            <div class="form-group">
                <a href="<?= base_auth('permissions') ?>" class="btn btn-secondary btn-pill">Kembali</a>
                <?php echo form_submit('submit', 'Create Permission', 'class="btn btn-primary btn-pill"'); ?>
                <button class="btn btn-primary btn-pill loader" type="button" disabled style="display: none;">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
            </div>

        </form>
    </div>
</div>