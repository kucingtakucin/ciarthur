<div class="modal fade" id="modal_tambah" role="dialog" aria-labelledby="modal-popin" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form class="needs-validation" id="form_tambah" method="post" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Form Tambah Data</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="perm_key">Permission Key</label>
                                <?php echo form_input('perm_key', set_value('perm_key'), 'class="form-control" id="tambah_perm_key" required autocomplete="off"'); ?>
                                <div class="invalid-feedback text-danger">Permission key wajib diisi</div>
                                <div class="valid-feedback text-success">Looks good</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="perm_name">Permission Name</label>
                                <?php echo form_input('perm_name', set_value('perm_name'), 'class="form-control" id="tambah_perm_key" required autocomplete="off"'); ?>
                                <div class="invalid-feedback text-danger">Permission name wajib diisi</div>
                                <div class="valid-feedback text-success">Looks good</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal" data-original-title="" title="">Close</button>
                    <button class="btn btn-primary" type="submit" data-original-title="" title="">Submit Data</button>
                    <button class="btn btn-primary loader" type="button" disabled style="display: none;">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END Pop In Modal -->