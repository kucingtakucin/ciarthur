<div class="modal fade" id="modal_ubah" role="dialog" aria-labelledby="modal-popin" aria-hidden="true">
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
                                <input type="text" id="ubah_nim" class="form-control" name="nim" required autocomplete="off" placeholder="Masukkan NIM">
                                <?= validation_feedback("nim", "wajib diisi") ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12" for="nama">Nama</label>
                                <input type="text" id="ubah_nama" class="form-control" name="nama" required autocomplete="off" placeholder="Masukkan Nama">
                                <?= validation_feedback("nama", "wajib diisi") ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12" for="angkatan">Angkatan</label>
                                <input type="number" id="ubah_angkatan" class="form-control" name="angkatan" required autocomplete="off" placeholder="Masukkan Angkatan">
                                <?= validation_feedback("angkatan", "wajib diisi dan wajib angka") ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12" for="fakultas">Fakultas</label>
                                <select name="fakultas_id" id="ubah_select_fakultas" required class="form-control select_fakultas"></select>
                                <?= validation_feedback("fakultas", "wajib dipilih") ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12" for="prodi">Prodi</label>
                                <select name="prodi_id" id="ubah_select_prodi" class="form-control select_prodi" required disabled></select>
                                <?= validation_feedback("prodi", "wajib dipilih") ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12" for="foto">Foto</label>
                                <small id="ubah_lihat" class="text-danger"></small>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="ubah_foto" name="foto" aria-describedby="inputGroupFileAddon01">
                                        <label class="custom-file-label" for="foto">Choose file</label>
                                        <?= validation_tooltip("foto", "wajib diupload") ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12" for="latitude">Latitude</label>
                                <input type="text" id="ubah_latitude" class="form-control" name="latitude" required autocomplete="off" placeholder="Masukkan Latitude">
                                <?= validation_feedback("latitude", "wajib diisi") ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12" for="longitude">Longitude</label>
                                <input type="text" id="ubah_longitude" class="form-control" name="longitude" required autocomplete="off" placeholder="Masukkan Longitude">
                                <?= validation_feedback("longitude", "wajib diisi") ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="height: 50vh;">
                            <div id="map-ubah" style="width: 100%; height: 100%; z-index: 0;"></div>
                        </div>
                    </div>
                    <input type="hidden" name="old_foto" id="ubah_old_foto">
                    <input type="hidden" name="old_foto_thumb" id="ubah_old_foto_thumb">
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