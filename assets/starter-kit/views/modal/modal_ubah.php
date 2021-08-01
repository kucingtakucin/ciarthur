<div class="modal fade" id="modal_ubah" tabindex="-1" role="dialog" aria-labelledby="modal-popin" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form class="needs-validation" id="form_ubah" method="post" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Form Ubah Data</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12" for="nim">NIM</label>
                                <input type="text" id="nim" class="form-control" name="nim" required autocomplete="off" placeholder="Masukkan NIM">
                                <div class="invalid-feedback text-danger">Please choose a unique and valid nim</div>
                                <div class="valid-feedback text-success">Looks good</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12" for="nama">Nama</label>
                                <input type="text" id="nama" class="form-control" name="nama" required autocomplete="off" placeholder="Masukkan Nama">
                                <div class="invalid-feedback text-danger">Please choose a unique and valid nama</div>
                                <div class="valid-feedback text-success">Looks good</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12" for="angkatan">Angkatan</label>
                                <input type="number" id="angkatan" class="form-control" name="angkatan" required autocomplete="off" placeholder="Masukkan Angkatan">
                                <div class="invalid-feedback text-danger">Please choose a unique and valid angkatan</div>
                                <div class="valid-feedback text-success">Looks good</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12" for="fakultas">Fakultas</label>
                                <select name="fakultas_id" id="select_fakultas" required class="form-control select_fakultas"></select>
                                <div class="invalid-feedback text-danger">Please choose a unique and valid fakultas</div>
                                <div class="valid-feedback text-success">Looks good</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12" for="prodi">Prodi</label>
                                <select name="prodi_id" id="select_prodi" class="form-control select_prodi" required disabled></select>
                                <div class="invalid-feedback text-danger">Please choose a unique and valid prodi</div>
                                <div class="valid-feedback text-success">Looks good</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12" for="foto">Foto</label>
                                <small id="lihat" class="text-danger"></small>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="foto" name="foto" aria-describedby="inputGroupFileAddon01">
                                        <label class="custom-file-label" for="foto">Choose file</label>
                                        <div class="invalid-tooltip">Please choose a unique and valid foto</div>
                                        <div class="valid-tooltip">Looks good</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="old_foto" id="old_foto">
                    <input type="hidden" name="old_foto_thumb" id="old_foto_thumb">
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