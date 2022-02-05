<script>
	let datatable_permission;
	const BASE_URL = "<?= base_url($uri_segment) ?>"

	// Document ready
	$(() => {
		load_datatable_permission()
	})
</script>

<script>
	/**
	 * Keperluan DataTable, Datepicker, Summernote dan BsCustomFileInput
	 */
	// ================================================== //
	const load_datatable_permission = async () => {
		datatable_permission = $('#datatable').DataTable({
			serverSide: true,
			processing: false,
			destroy: true,
			dom: `
				<"d-flex flex-row justify-content-end flex-wrap mb-2"B>
                <"d-flex flex-row justify-content-between mb-2"lf>
                rt
                <"d-flex flex-row justify-content-between mt-2"ip>`,
			buttons: {
				/** Tombol-tombol Export & Tambah Data */
				buttons: [{
					className: 'btn btn-info m-2 text-white',
					text: $('<i>', {
						class: 'fa fa-plus'
					}).prop('outerHTML') + ' Tambah Permission', // Tambah Data
					action: (e, dt, node, config) => {
						$('#modal_tambah').modal('show');
					}
				}, ],
				dom: {
					button: {
						className: 'btn'
					},
					buttonLiner: {
						tag: null
					}
				}
			},
			ajax: {
				url: BASE_URL + 'data_permission',
				type: 'POST',
				dataType: 'JSON',
			},
			columnDefs: [{
					targets: [0, 1, 2, 3], // Sesuaikan dengan jumlah kolom
					className: 'text-center'
				},
				{
					targets: [0, 3],
					searchable: false,
					orderable: false,
				},
			],
			order: [],
			columns: [{ // 0
					title: '#',
					name: '#',
					data: 'no',
				},
				{ // 1
					title: 'Permission Key',
					name: 'perm_key',
					data: 'perm_key',
				},
				{ // 2
					title: 'Permission Name',
					name: 'perm_name',
					data: 'perm_name',
				},
				{ // 3
					title: 'Aksi',
					name: 'aksi',
					data: 'aksi',

				},
			],
			initComplete: function(event) {
				$(this).on('click', '.btn_edit', function(event) {
					event.preventDefault()
					$get(this)
				});

				$(this).on('click', '.btn_delete', function(event) {
					event.preventDefault()
					$delete(this);
				});

				$('[title]').tooltip()
				// ================================================== //
			},
		})
	}

	// ================================================== //
</script>

<script>
	/**
	 * Keperluan CRUD
	 */
	// ================================================== //

	const $get = (element) => {
		let row = datatable_permission.row($(element).closest('tr')).data();
		$('#modal_ubah').modal('show');
		$('#form_ubah input#id[name=id]').val(row.id)
		$('#form_ubah input#ubah_perm_name[name=perm_name]').val(row.perm_name);
		$('#form_ubah input#ubah_perm_key[name=perm_key]').val(row.perm_key);
	}

	const $insert = async (form) => {
		loading()

		let formData = new FormData(form);

		axios.post(BASE_URL + 'add_permission', formData)
			.then(res => {
				Swal.fire({
					icon: 'success',
					title: 'Success!',
					text: res.data.message,
					showConfirmButton: false,
					timer: 1500
				}).then(() => {
					// socket.emit('auth-crud-permission')
					datatable_permission.ajax.reload()
				})

			}).catch(err => {
				console.error(err);
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					html: err.response.data.message,
					// text: err.response.statusText
				})
			}).then(() => {
				$('#form_tambah button[type=submit]').show();
				$('#form_tambah button.loader').hide();
				$('#form_tambah').trigger('reset');
				$('#form_tambah select').val(null).trigger('change')
				$('#form_tambah').removeClass('was-validated')
				$('#modal_tambah').modal('hide');
			})
	}

	const $update = async (form) => {
		loading()

		let formData = new FormData(form);

		axios.post(BASE_URL + 'update_permission', formData)
			.then(res => {
				$('#form_ubah button[type=submit]').hide();
				$('#form_ubah button.loader').show();

				Swal.fire({
					icon: 'success',
					title: 'Success!',
					text: res.data.message,
					showConfirmButton: false,
					timer: 1500
				}).then(() => {
					// socket.emit('backend-crud-mahasiswa', {})
					datatable_permission.ajax.reload()
				})

			}).catch(err => {
				console.error(err);
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					html: err.response.data.message,
					// text: err.response.statusText
				})
			}).then(() => {
				$('#form_ubah button[type=submit]').show();
				$('#form_ubah button.loader').hide();
				$('#form_ubah').trigger('reset');
				$('#form_ubah select').val(null).trigger('change')
				$('#form_ubah').removeClass('was-validated')
				$('#modal_ubah').modal('hide');
			})
	}

	const $delete = async (element) => {
		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!',
			reverseButtons: true,
		}).then(async (result) => {
			if (result.isConfirmed) {
				loading()

				let formData = new FormData();
				formData.append('id', $(element).data('id'));

				axios.post(BASE_URL + 'delete_permission', formData)
					.then(res => {
						Swal.fire({
							icon: 'success',
							title: 'Success!',
							text: res.data.message,
							showConfirmButton: false,
							timer: 1500
						}).then(() => {
							// socket.emit('auth-crud-permission', {})
							datatable_permission.ajax.reload()

						})

					}).catch(err => {
						console.error(err);
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							// html: err.response.data.message,
							text: err.response.statusText
						})
					})
			}
		})
	}
</script>

<script>
	/**
	 * Keperluan event click tombol, reset, export, validasi dan submit form
	 */
	// ================================================== //
	$('#modal_tambah').on('hidden.bs.modal', function(event) {
		$('#form_tambah').trigger('reset')
		$('#form_tambah').removeClass('was-validated')
	})

	$('#modal_ubah').on('hidden.bs.modal', function(event) {
		$('#form_ubah').trigger('reset')
		$('#form_ubah').removeClass('was-validated')
	})

	$('#form_tambah').submit(function(event) {
		event.preventDefault()
		if (this.checkValidity()) {
			$insert(this);
		}
	});

	$('#form_ubah').submit(function(event) {
		event.preventDefault();
		if (this.checkValidity()) {
			$update(this);
		}
	});
</script>