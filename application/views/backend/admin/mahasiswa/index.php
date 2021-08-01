<div class="card">
    <div class="card-header">
        <h5 class="text-center">Kelola Data Mahasiswa</h5>
        <p class="text-muted text-center mb-0">Sistem Informasi <?= sistem()->nama ?></p>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="filter_tanggal">Tanggal</label>
                    <input autocomplete="off" type="text" id="filter_tanggal" name="filter_tanggal" class="form-control datepicker" placeholder="Pilih tanggal">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="filter_fakultas">Fakultas</label>
                    <select id="filter_fakultas" name="filter_fakultas" class="js-select2 form-control">
                        <option></option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group row">
                    <label for="filter_prodi">Prodi</label>
                    <select id="filter_prodi" name="filter_prodi" class="js-select2 form-control" disabled>
                        <option></option>
                    </select>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-12">
                <button type="button" class="btn btn-primary btn-pill float-right" id="tombol_tambah"><i class="fa fa-plus"></i> Tambah Data</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 table-responsive">
                <table id="table_data" class="table table-striped table-hover"></table>
            </div>
        </div>
    </div>
</div>

<!-- END Page Content -->