<div class="card">
	<div class="card-header">
		<div class="row">
			<div class="col-sm-8 offset-sm-2 d-flex flex-column align-items-center justify-content-center">
				<img src="data:image/png;base64,<?= $barcode ?>" class="img-fluid mb-2" alt="Barcode">
				<h5 class="text-center">Data <?= $title ?></h5>
				<p class="text-muted text-center mb-0"><?= sistem()->nama ?></p>
			</div>
			<div class="col-sm-2">
				<img src="<?= $qrcode ?>" class="img-thumbnail" alt="QrCode">
			</div>
		</div>
	</div>
	<div class="card-body map-body-container">
		<div id="map"></div>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="row">
			<div class="col-md-12 table-responsive">
				<table id="datatable" class="display"></table>
			</div>
		</div>
	</div>
</div>

<!-- END Page Content -->