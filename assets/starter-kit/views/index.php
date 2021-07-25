<style>
    .glow:hover {
        box-shadow: 0px 0px 5px 0px rgb(67, 165, 245);
    }
</style>
<!-- Page Content -->
<div class="content">

    <h2 class="content-heading"><?= $title ?></h2>

    <div class="block block-rounded glow">
        <div class="block-content" style="background-image: url('<?= base_url('uploads/img/bg.jpg') ?>'); background-size: 100% 100%;image-rendering: pixelated;">
            <div class="py-20 text-center">
                <h1 class="h3 mb-2">Kelola Data ...</h1>
                <p class="mb-10 text-muted">
                    <em>Sistem Informasi ...</em>
                </p>
            </div>
        </div>
    </div>

    <div class="block block-rounded glow">
        <div class="block-header bg-primary-darker">
            <h3 class="block-title text-white">FILTER DATA</h3>
        </div>

        <div class="block-content">
            <div class="row">

                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-12">Kabupaten</label>
                        <div class="col-md-12">
                            <select id="select_kabupaten" class="js-select2">
                                <option selected value="all">all</option>
                                <option value="data_1">data 1</option>
                                <option value="data_2">data 2</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-12">Kecamatan</label>
                        <div class="col-md-12">
                            <select id="select_kecamatan" class="js-select2">
                                <option selected value="all">all</option>
                                <option value="data_1">data 1</option>
                                <option value="data_2">data 2</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-12">Kelurahan</label>
                        <div class="col-md-12">
                            <select id="select_kelurahan" class="js-select2">
                                <option selected value="all">all</option>
                                <option value="data_1">data 1</option>
                                <option value="data_2">data 2</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="block block-rounded glow">
        <div class="block-header bg-primary-darker">
            <h3 class="block-title text-white">Table Data</h3>
            <button type="button" class="btn btn-primary btn-square float-right" id="tombol_tambah"><i class="fa fa-plus"></i> Tambah Data</button>
        </div>
        <div class="block-content">

            <table widht="100%" id="table_data" class="table table-striperd">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Role</th>
                        <th>Username</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="table_body"></tbody>
            </table>

        </div>
    </div>

</div>
<!-- END Page Content -->