<form id="form_tambah" method="post" enctype="multipart/form-data">
	<div class="form-group">
		<div class="row m-0">

			<div class="col-sm-3 text-left">
				<label class="mt-2" for="input_tambah_nama">Kategori</label>
			</div>
			<div class="col-sm-9">
				<input type="text" id="input_tambah_nama" name="nama" class="form-control" placeholder="Masukkan nama kategori" autocomplete="off">
				<?= validation_feedback('tambah', 'nama') ?>
			</div>

		</div>
	</div>
</form>
