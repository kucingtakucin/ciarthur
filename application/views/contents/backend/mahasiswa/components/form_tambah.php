<form id="form_tambah" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_tambah_nim">NIM</label>
            </div>

            <div class="col-sm-9">
                <input type="text" id="input_tambah_nim" name="nim" class="form-control" placeholder="Masukkan NIM" autocomplete="off" onkeydown="return event.keyCode != 13;">
                <div class="invalid-feedback text-danger text-left" style="display: none;" id="error_tambah_nim"></div>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_tambah_nama">Nama</label>
            </div>

            <div class="col-sm-9">
                <input type="text" id="input_tambah_nama" name="nama" class="form-control" placeholder="Masukkan Nama" autocomplete="off" onkeydown="return event.keyCode != 13;">
                <div class="invalid-feedback text-danger text-left" style="display: none;" id="error_tambah_nama"></div>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_tambah_angkatan">Angkatan</label>
            </div>

            <div class="col-sm-9">
                <input type="number" id="input_tambah_angkatan" name="angkatan" class="form-control" placeholder="Masukkan Tahun Angkatan" autocomplete="off" onkeydown="return event.keyCode != 13;">
                <div class="invalid-feedback text-danger text-left" style="display: none;" id="error_tambah_angkatan"></div>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="select_tambah_fakultas_id">Fakultas</label>
            </div>

            <div class="col-sm-9 text-left">
                <select id="select_tambah_fakultas_id" name="fakultas_id" class="form-control" onkeydown="return event.keyCode != 13;">
                </select>
                <div class="invalid-feedback text-danger text-left" style="display: none;" id="error_tambah_fakultas_id"></div>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">
            <div class="col-sm-3 text-left">
                <label class="mt-2" for="select_tambah_prodi_id">Prodi</label>
            </div>

            <div class="col-sm-9 text-left">
                <select id="select_tambah_prodi_id" name="prodi_id" disabled class="form-control" onkeydown="return event.keyCode != 13;">
                </select>
                <div class="invalid-feedback text-danger text-left" style="display: none;" id="error_tambah_prodi_id"></div>
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
                        <input type="file" id="input_tambah_foto" name="foto" class="form-control text-left" onkeydown="return event.keyCode != 13;">
                        <label class="custom-file-label overflow-hidden" for="input_tambah_foto">Choose file</label>
                    </div>
                </div>
                <div class="invalid-feedback text-danger text-left" style="display: none;" id="error_tambah_foto"></div>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_tambah_latitude">Latitude</label>
            </div>

            <div class="col-sm-9">
                <input type="text" id="input_tambah_latitude" name="latitude" class="form-control" placeholder="Masukkan Latitude" autocomplete="off" onkeydown="return event.keyCode != 13;">
                <div class="invalid-feedback text-danger text-left" style="display: none;" id="error_tambah_latitude"></div>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_tambah_longitude">Longitude</label>
            </div>

            <div class="col-sm-9">
                <input type="text" id="input_tambah_longitude" name="longitude" class="form-control" placeholder="Masukkan Latitude" autocomplete="off" onkeydown="return event.keyCode != 13;">
                <div class="invalid-feedback text-danger text-left" style="display: none;" id="error_tambah_longitude"></div>
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