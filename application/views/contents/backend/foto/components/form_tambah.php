<form id="form_tambah" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_tambah_judul">Judul</label>
            </div>

            <div class="col-sm-9">
                <input type="text" id="input_tambah_judul" name="judul" class="form-control" placeholder="Masukkan judul" autocomplete="off" onkeydown="return event.keyCode != 13;">
                <?= validation_feedback('tambah', 'judul') ?>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_tambah_deskripsi">Deskripsi</label>
            </div>

            <div class="col-sm-9">
                <input type="text" id="input_tambah_deskripsi" name="deskripsi" class="form-control" placeholder="Masukkan deskripsi" autocomplete="off" onkeydown="return event.keyCode != 13;">
                <?= validation_feedback('tambah', 'deskripsi') ?>
            </div>

        </div>
    </div>

    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_tambah_gambar">Gambar</label>
            </div>

            <div class="col-sm-9">
                <div class="input-group">
                    <div class="custom-file text-left">
                        <input type="file" id="input_tambah_gambar" name="gambar" class="form-control text-left" onkeydown="return event.keyCode != 13;">
                        <label class="custom-file-label overflow-hidden" for="input_tambah_gambar">Choose file</label>
                    </div>
                </div>
                <?= validation_feedback('tambah', 'gambar') ?>
            </div>

            <div class="col-sm-9 offset-sm-3 text-left mt-2">
                <img id="show-cover" class="rounded img-fluid" src="<?= base_url('assets/unnamed.png') ?>" alt="Image Cover">
            </div>

        </div>
    </div>

</form>