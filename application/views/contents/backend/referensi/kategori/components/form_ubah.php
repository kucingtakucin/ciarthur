<form id="form_ubah" method="post" enctype="multipart/form-data">
	<div class="form-group">
		<div class="row m-0">

			<div class="col-sm-3 text-left">
				<label class="mt-2" for="input_ubah_nama">Kategori</label>
			</div>
			<div class="col-sm-9">
				<input type="text" id="input_ubah_nama" name="nama" class="form-control" placeholder="Masukkan nama kategori" autocomplete="off">
				<?= validation_feedback('ubah', 'nama') ?>
			</div>

		</div>
	</div>
</form>
