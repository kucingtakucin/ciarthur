<form id="form_import" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <div class="mt-2">Template Excel</div>
            </div>

            <div class="col-sm-9 text-left">
                <button class="btn btn-success tombol-download-template" id="downloadTemplateExcel" type="button">
                    <i class="fa fa-download"></i>
                    Download
                </button>
                <button class="btn btn-primary loader" type="button" disabled style="display: none;">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_file_excel">Upload File</label>
            </div>

            <div class="col-sm-9">
                <div class="input-group">
                    <div class="custom-file text-left">
                        <input type="file" id="input_file_excel" name="file_excel" class="form-control text-left">
                        <label class="custom-file-label overflow-hidden" for="input_file_excel">Choose file</label>
                    </div>
                </div>
                <?= validation_feedback('file', 'excel') ?>
            </div>

        </div>
    </div>

</form>