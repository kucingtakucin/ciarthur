<form id="form_ubah" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_ubah_nim">NIM</label>
            </div>

            <div class="col-sm-9">
                <input type="text" id="input_ubah_nim" name="nim" class="form-control" placeholder="Masukkan NIM" autocomplete="off">
                <?= validation_feedback('ubah', 'nim') ?>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_ubah_nama">Nama</label>
            </div>

            <div class="col-sm-9">
                <input type="text" id="input_ubah_nama" name="nama" class="form-control" placeholder="Masukkan Nama" autocomplete="off">
                <?= validation_feedback('ubah', 'nama') ?>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_ubah_angkatan">Angkatan</label>
            </div>

            <div class="col-sm-9">
                <input type="number" id="input_ubah_angkatan" name="angkatan" class="form-control" placeholder="Masukkan Tahun Angkatan" autocomplete="off">
                <?= validation_feedback('ubah', 'angkatan') ?>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="select_ubah_fakultas_id">Fakultas</label>
            </div>

            <div class="col-sm-9 text-left">
                <select id="select_ubah_fakultas_id" name="fakultas_id" class="form-control">
                </select>
                <?= validation_feedback('ubah', 'fakultas_id') ?>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">
            <div class="col-sm-3 text-left">
                <label class="mt-2" for="select_ubah_prodi_id">Prodi</label>
            </div>

            <div class="col-sm-9 text-left">
                <select id="select_ubah_prodi_id" name="prodi_id" disabled class="form-control">
                </select>
                <?= validation_feedback('ubah', 'prodi_id') ?>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_ubah_foto">Foto</label>
            </div>

            <div class="col-sm-9">
                <div class="input-group">
                    <div class="custom-file text-left">
                        <input type="file" id="input_ubah_foto" name="foto" class="form-control text-left">
                        <label class="custom-file-label overflow-hidden" for="input_ubah_foto">Choose file</label>
                    </div>
                </div>
                <?= validation_feedback('ubah', 'foto') ?>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_ubah_latitude">Latitude</label>
            </div>

            <div class="col-sm-9">
                <input type="text" id="input_ubah_latitude" name="latitude" class="form-control" placeholder="Masukkan Latitude" autocomplete="off">
                <?= validation_feedback('ubah', 'latitude') ?>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_ubah_longitude">Longitude</label>
            </div>

            <div class="col-sm-9">
                <input type="text" id="input_ubah_longitude" name="longitude" class="form-control" placeholder="Masukkan Latitude" autocomplete="off">
                <?= validation_feedback('ubah', 'longitude') ?>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0 map-swal-container">
            <div class="col-sm-12 text-left">
                <div id="map-ubah"></div>
            </div>
        </div>
    </div>

</form>