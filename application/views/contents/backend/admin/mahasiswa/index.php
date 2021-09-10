<style>
    div.dataTables_filter,
    div.dataTables_length {
        margin: 0 !important;
    }

    .legend {
        padding: 12px 16px;
        background: white;
        background: rgba(255, 255, 255, 0.8);
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        border-radius: 5px;
        color: #555;
    }

    .legend h3 {
        text-align: center;
        color: #777;
        margin: 0;
    }

    #map,
    #map-tambah,
    #map-ubah {
        width: 100%;
        height: 100%;
        z-index: 0;
    }

    .map-body-container {
        height: 100vh;
    }

    .map-modal-container {
        height: 50vh;
    }

</style>

<div class="card">
    <div class="card-header">
        <h5 class="text-center">Kelola Data Mahasiswa</h5>
        <p class="text-muted text-center mb-0">Sistem Informasi <?= sistem()->nama ?></p>
    </div>
</div>

<div class="card">
    <div class="card-body map-body-container">
        <div id="map"></div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table id="datatable" class="table table-striped table-hover"></table>
            </div>
        </div>
    </div>
</div>

<!-- END Page Content -->