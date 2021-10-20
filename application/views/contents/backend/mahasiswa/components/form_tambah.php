<form id="form_tambah" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_tambah_nim">NIM</label>
            </div>

            <div class="col-sm-9">
                <input type="text" id="input_tambah_nim" name="nim" class="form-control" placeholder="Masukkan NIM" autocomplete="off">
                <?= validation_feedback('tambah', 'nim') ?>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_tambah_nama">Nama</label>
            </div>

            <div class="col-sm-9">
                <input type="text" id="input_tambah_nama" name="nama" class="form-control" placeholder="Masukkan Nama" autocomplete="off">
                <?= validation_feedback('tambah', 'nama') ?>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_tambah_angkatan">Angkatan</label>
            </div>

            <div class="col-sm-9">
                <input type="number" id="input_tambah_angkatan" name="angkatan" class="form-control" placeholder="Masukkan Tahun Angkatan" autocomplete="off">
                <?= validation_feedback('tambah', 'angkatan') ?>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="select_tambah_fakultas_id">Fakultas</label>
            </div>

            <div class="col-sm-9 text-left">
                <select id="select_tambah_fakultas_id" name="fakultas_id" class="form-control">
                </select>
                <?= validation_feedback('tambah', 'fakultas_id') ?>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">
            <div class="col-sm-3 text-left">
                <label class="mt-2" for="select_tambah_prodi_id">Prodi</label>
            </div>

            <div class="col-sm-9 text-left">
                <select id="select_tambah_prodi_id" name="prodi_id" disabled class="form-control">
                </select>
                <?= validation_feedback('tambah', 'prodi_id') ?>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_tambah_foto">Foto</label>
            </div>

            <div class="col-sm-9">
                <div class="input-group">
                    <div class="custom-file text-left">
                        <input type="file" id="input_tambah_foto" name="foto" class="form-control text-left">
                        <label class="custom-file-label overflow-hidden" for="input_tambah_foto">Choose file</label>
                    </div>
                </div>
                <?= validation_feedback('tambah', 'foto') ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_tambah_latitude">Latitude</label>
            </div>

            <div class="col-sm-9">
                <input type="text" id="input_tambah_latitude" name="latitude" class="form-control" placeholder="Masukkan Latitude" autocomplete="off">
                <?= validation_feedback('tambah', 'latitude') ?>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_tambah_longitude">Longitude</label>
            </div>

            <div class="col-sm-9">
                <input type="text" id="input_tambah_longitude" name="longitude" class="form-control" placeholder="Masukkan Latitude" autocomplete="off">
                <?= validation_feedback('tambah', 'longitude') ?>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0 map-swal-container">
            <div class="col-sm-12 text-left">
                <div id="map-tambah"></div>
            </div>
        </div>
    </div>

</form>