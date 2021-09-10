<div class="modal fade" id="modal_import" role="dialog" aria-labelledby="modal-popin" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="needs-validation" id="form_import" method="post" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Form Import Data</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"
                        data-original-title="" title=""><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="mb-1">
                                    Template Excel
                                </div>
                                <button class="btn btn-success" id="downloadTemplateExcel" type="button">
                                    <i class="fa fa-download"></i>
                                    Download
                                </button>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="nim">Upload File</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="import_file_excel"
                                            id="import_file_excel" required aria-describedby="inputGroupFileAddon01">
                                        <label class="custom-file-label" for="foto">Choose file</label>
                                        <?= validation_tooltip('dokumen', 'wajib diupload') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal" data-original-title=""
                        title="">Close</button>
                    <button class="btn btn-primary" type="submit" data-original-title="" title="">Import Data</button>
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
