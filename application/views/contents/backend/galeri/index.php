<ul class="nav nav-pills nav-primary" role="tablist">
	<li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#galeri-tab" role="tab" aria-controls="galeri-tab" title="Galeri"><i class="icofont icofont-ui-image"></i>Galeri</a></li>
	<li class="nav-item"><a class="nav-link disabled" data-toggle="pill" href="#foto-tab" role="tab" aria-controls="foto-tab" title="Foto"><i class="icofont icofont-image"></i>Foto</a></li>
</ul>

<div class="tab-content m-t-30">
	<div class="tab-pane fade active show" id="galeri-tab" role="tabpanel">
		<div class="card">
			<div class="card-header">
				<h5 class="text-center">Data <?= $title ?></h5>
				<p class="text-muted text-center mb-0"><?= sistem()->nama ?></p>
			</div>
		</div>

		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12 table-responsive">
						<table id="datatable_galeri" class="display"></table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="tab-pane fade" id="foto-tab" role="tabpanel">
		<div class="card">
			<div class="card-header">
				<h5 class="text-center">Data Foto</h5>
				<p class="text-muted text-center mb-0"><?= sistem()->nama ?></p>
			</div>
		</div>

		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12 table-responsive">
						<table id="datatable_foto" class="display"></table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END Page Content -->