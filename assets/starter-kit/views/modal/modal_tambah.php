<!-- Pop In Modal -->
<div class="modal fade" id="modal_tambah" tabindex="-1" role="dialog" aria-labelledby="modal-popin" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popin" role="document">
        <form class="js-validation-signin" action="#" id="form_data" method="post">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">FORM TAMBAH DATA</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group row">
                            <label class="col-12">Role</label>
                            <div class="col-md-12">
                                <select class="js-select2 form-control" name="role">
                                    <?php foreach ($role as $role) : ?>
                                        <option value="<?= $role->role_id ?>"><?= $role->nama_role ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-12">Username</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="username" placeholder="masukkan username">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-12">Password</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="password" placeholder="masukkan password">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-alt-success">
                        <i class="fa fa-check"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- END Pop In Modal -->