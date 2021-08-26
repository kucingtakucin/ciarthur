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
    <div class="card-body" style="height: 100vh;">
        <div id="map" style="width: 100%; height: 100%; z-index: 0;"></div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-12 d-flex justify-content-end flex-row flex-wrap align-content-center">
                <button type="button" class="btn btn-primary btn-pill mr-2 mt-2" id="tombol_export_word"><i class="fa fa-file-word-o"></i> Export DOCX</button>
                <button type="button" class="btn btn-danger btn-pill mr-2 mt-2" id="tombol_export_pdf"><i class="fa fa-file-pdf-o"></i> Export PDF</button>
                <button type="button" class="btn btn-success btn-pill mr-2 mt-2" id="tombol_export_excel"><i class="fa fa-file-excel-o"></i> Export XLSX</button>
                <button type="button" class="btn btn-info btn-pill mt-2" id="tombol_tambah"><i class="fa fa-plus"></i> Tambah Data</button>
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