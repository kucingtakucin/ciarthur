<script>
	const BASE_URL = "<?= base_url($uri_segment) ?>"
	let datatable;

	// Document ready
	$(() => {
		load_datatable()
	});
</script>

<script>
	//=============================================================//
	//======================== DATATABLES =========================//
	//=============================================================//

	/**
	 * Keperluan DataTable, Datepicker, Summernote dan BsCustomFileInput
	 */
	// ================================================== //
	const load_datatable = () => {
		datatable = $('#datatable').DataTable({
			serverSide: true,
			processing: true,
			destroy: true,
			dom: `<"dt-custom-filter mb-2 d-block">
                <"d-flex flex-row justify-content-end flex-wrap mb-2"B>
                <"d-flex flex-row justify-content-between mb-2"lf>
                rt
                <"d-flex flex-row justify-content-between mt-2"ip>`,
			scrollX: true,
			buttons: {
				/** Tombol-tombol Export & Tambah Data */
				buttons: [{
					className: 'btn btn-info m-2 text-white',
					text: $('<i>', {
						class: 'fa fa-plus'
					}).prop('outerHTML') + ' Tambah Data', // Tambah Data
					action: (e, dt, node, config) => $insert()
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
				url: BASE_URL + 'data',
				type: 'POST',
				dataType: 'JSON',
				error: (err) => {
					if (!err?.responseJSON &&
						err.status === 403) _handle_csrf()
				}
			},
			order: [],
			columnDefs: [{
					targets: [0, 2], // Sesuaikan dengan jumlah kolom
					className: 'text-center'
				},
				{
					targets: [0, 2],
					searchable: false,
					orderable: false,
				},
			],
			columns: [{ // 0
					title: '#',
					name: '#',
					data: 'no',
				},
				{ // 1
					title: 'Fakultas',
					name: 'nama',
					data: 'nama',
					className: 'input_nama'
				},
				{ // 2
					title: 'Aksi',
					name: 'aksi',
					data: 'aksi',
				},
			],
			initComplete: function(event) {
				$(this).on('click', '.btn_edit', function(event) {
					event.preventDefault()
					$update(this);
				});

				$(this).on('click', '.btn_delete', function(event) {
					event.preventDefault()
					$delete(this);
				});

				$(this).on('dblclick', 'td.input_nama:not(.clicked)', function(event) {
					event.preventDefault()
					$(this).addClass('clicked')

					let val = $(this).html();
					let html = $('<input>', {
						type: 'text',
						name: 'nama',
						id: 'inline_ubah_nama',
						class: 'form-control',
						autocomplete: 'off',
						value: val
					}).prop('outerHTML')

					$(this).html(html)
					$(this).find('input').focus()
				})

				$(this).on('blur', 'td.input_nama.clicked input', function(event) {
					event.preventDefault();
					$inline(this, 'nama');
				})

				$('[title]').tooltip()
				bsCustomFileInput.init()
			},
		})

		// ================================================== //
	}
</script>

<script>
	//=============================================================//
	//=========================== CRUD ============================//
	//=============================================================//

	/**
	 * Keperluan CRUD
	 */
	// ================================================== //

	const $insert = () => {
		Swal.fire({
			title: 'Form Tambah Data',
			width: '800px',
			icon: 'info',
			html: `<?= view("contents/$uri_segment/components/form_tambah", [], true) ?>`,
			confirmButtonText: '<i class="fa fa-check-square-o"></i> Simpan Data',
			showCancelButton: true,
			focusConfirm: false,
			showLoaderOnConfirm: true,
			allowOutsideClick: false,
			allowEscapeKey: false,
			allowEnterKey: false,
			showCloseButton: true,
			reverseButtons: true,
			preConfirm: async () => {
				let formData = new FormData(document.getElementById('form_tambah'));

				$('#form_tambah .invalid-feedback').slideUp(500)
				$('#form_tambah .is-invalid').removeClass('is-invalid')

				let response = await axios.post(BASE_URL + 'insert', formData)
					.then(res => res.data.message)
					.catch(err => {
						if (!err?.response) {
							Swal.showValidationMessage('Not Allowed');
							_handle_csrf();
						}

						let errors = err?.response.data?.errors;
						if (errors && typeof errors === 'object') {
							Object.entries(errors).map(([key, value]) => {
								$(`#input_tambah_${key}`).addClass('is-invalid')
								$(`#error_tambah_${key}`).html(value).slideDown(500)
							})
						}
						Swal.showValidationMessage(err.response.data?.message ?? err.response.statusText);
					})

				return {
					data: response
				}
			}
		}).then((result) => {
			if (result.value) {
				Swal.fire({
					title: 'Berhasil',
					icon: 'success',
					text: result.value.data,
					showConfirmButton: false,
					allowEscapeKey: false,
					allowOutsideClick: false,
					timer: 1500
				}).then(() => datatable.ajax.reload())
			}
		})
	}

	const $update = (element) => {
		let row = datatable.row($(element).closest('tr')).data();

		Swal.fire({
			title: 'Form Ubah Data',
			width: '800px',
			html: `<?= view("contents/$uri_segment/components/form_ubah", [], true) ?>`,
			confirmButtonText: '<i class="fa fa-check-square-o"></i> Simpan Data',
			showCancelButton: true,
			focusConfirm: false,
			showLoaderOnConfirm: true,
			allowOutsideClick: false,
			allowEscapeKey: false,
			allowEnterKey: false,
			showCloseButton: true,
			reverseButtons: true,
			didOpen: () => {
				$('#form_ubah #input_ubah_nama').val(row.nama)
			},
			preConfirm: async () => {
				let formData = new FormData(document.getElementById('form_ubah'));
				formData.append('id', row.id)

				$('#form_ubah .invalid-feedback').slideUp(500)
				$('#form_ubah .is-invalid').removeClass('is-invalid')

				let response = await axios.post(BASE_URL + 'update', formData)
					.then(res => res.data.message)
					.catch(err => {
						if (!err?.response) {
							Swal.showValidationMessage('Not Allowed');
							_handle_csrf();
						}

						let errors = err.response.data?.errors;
						if (errors && typeof errors === 'object') {
							Object.entries(errors).map(([key, value]) => {
								$(`#input_ubah_${key}`).addClass('is-invalid')
								$(`#error_ubah_${key}`).html(value).slideDown(500)
							})
						}

						Swal.showValidationMessage(err.response.data?.message ?? err.response.statusText)
					})

				return {
					data: response
				}
			}
		}).then((result) => {
			if (result.value) {
				Swal.fire({
					title: 'Berhasil',
					icon: 'success',
					text: result.value.data,
					showConfirmButton: false,
					allowEscapeKey: false,
					allowOutsideClick: false,
					timer: 1500
				}).then(() => datatable.ajax.reload())
			}
		})
	}

	const $delete = async (element) => {
		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!',
			showCancelButton: true,
			showLoaderOnConfirm: true,
			allowOutsideClick: false,
			allowEscapeKey: false,
			allowEnterKey: false,
			showCloseButton: true,
			reverseButtons: true,
		}).then(async (result) => {
			if (result.isConfirmed) {
				loading()

				let formData = new FormData();
				formData.append('id', $(element).data('id'))

				axios.post(BASE_URL + 'delete', formData)
					.then(res => {
						Swal.fire({
							icon: 'success',
							title: 'Success!',
							text: res.data.message,
							showConfirmButton: false,
							timer: 1500
						}).then(() => {
							datatable.ajax.reload()
						})

					}).catch(err => {
						if (!err?.response) _handle_csrf();

						console.error(err);
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							text: err.response.statusText
						})
					})
			}
		})
	}

	const $inline = (element, name) => {
		$(element).closest(`td.input_${name}`).removeClass('clicked');
		let row = datatable.row($(element).closest('tr')).data();
		let val = $(element).val();

		let formData = new FormData();
		formData.append('id', row.id)
		formData.append(name, val)

		axios.post(BASE_URL + 'update', formData)
			.then(res => {
				$(element).closest(`td.input_${name}`).html(val)
				datatable.ajax.reload()
				toastr.success('Berhasil mengubah data', 'Sukses')
			})
			.catch(err => {
				if (!err?.response) {
					_handle_csrf();
					return;
				}

				let errors = err.response.data?.errors;
				if (errors && typeof errors === 'object') {
					toastr.error('Tidak boleh kosong', 'Gagal')
					Object.entries(errors).map(([key, value]) => {
						$(`#inline_ubah_${key}`).addClass('is-invalid')
					})
					$(element).focus()

					$(element).on('blur', (event) => {
						if ($(element).val()) $inline(element, name);
						else datatable.ajax.reload();
					})
				}
			})
	}
	// ================================================== //	
</script>
