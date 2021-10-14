<form id="form_tambah" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <div class="row m-0">
            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_tambah_nama">Fakultas</label>
            </div>
            <div class="col-sm-9">
                <input type="text" id="input_tambah_nama" name="nama" class="form-control" placeholder="Masukkan nama fakultas" autocomplete="off" onkeydown="return event.keyCode != 13;">
                <div class="invalid-feedback text-danger text-left" style="display: none;" id="error_tambah_nama"></div>
            </div>
        </div>
    </div>
</form>