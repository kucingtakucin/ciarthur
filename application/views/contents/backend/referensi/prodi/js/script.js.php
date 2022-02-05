<script>
	const BASE_URL = "<?= base_url($uri_segment) ?>"
	let datatable;
	// Document ready

	$(() => {
		load_datatable()
	});
</script>

<script>
	/**
	 * Keperluan DataTable, Datepicker, Summernote dan BsCustomFileInput
	 */
	// ================================================== //
	const load_datatable = () => {
		datatable = $('#datatable').DataTable({
			serverSide: true,
			processing: false,
			destroy: true,
			dom: `<"dt-custom-filter mb-2 d-block">
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
					}).prop('outerHTML') + ' Tambah Data', // Tambah Data
					action: (e, dt, node, config) => {
						// $('#modal_tambah').modal('show');
						$insert();
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
				url: BASE_URL + 'data',
				type: 'POST',
				dataType: 'JSON',
			},
			order: [],
			columnDefs: [{
					targets: [0, 3], // Sesuaikan dengan jumlah kolom
					className: 'text-center'
				},
				{
					targets: [0, 3],
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
					title: 'Prodi',
					name: 'nama',
					data: 'nama',
					className: 'input_nama',
				},
				{ // 2
					title: 'Fakultas',
					name: 'nama_fakultas',
					data: 'nama_fakultas',
					className: 'input_fakultas_id'
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
					$update(this);
				});

				$(this).on('click', '.btn_delete', function(event) {
					event.preventDefault()
					$delete(this);
				});

				$(this).on('dblclick', 'td.input_fakultas_id:not(.clicked)', function(event) {
					event.preventDefault()
					$(this).addClass('clicked')
					let row = datatable.row($(this).closest('tr')).data();

					let val = $(this).html();
					let html = $('<select>', {
						name: 'fakultas_id',
						id: 'inline_ubah_value',
						class: 'form-control',
						html: $('<option>', {
							html: ''
						})
					}).prop('outerHTML')

					$(this).html(html)
					$(this).find('select').select2({
						placeholder: 'Pilih Fakultas',
						width: '100%',
						ajax: {
							url: BASE_URL + 'get_fakultas',
							dataType: 'JSON',
							delay: 250,
							data: function(params) {
								return {
									search: params.term, // search term
								};
							},
							processResults: function(response) {
								let myResults = [];
								response.data.map(item => {
									myResults.push({
										'id': item.id,
										'text': item.nama
									});
								})
								return {
									results: myResults
								};
							}
						}
					})

					$(this).find('select')
						.append(new Option(row.nama_fakultas, row.fakultas_id, true, true))
						.trigger('change')
						.trigger('select2:select');

					$(this).find('select').select2('open')
				})

				$(this).on('dblclick', 'td.input_nama:not(.clicked)', function(event) {
					event.preventDefault()
					$(this).addClass('clicked')

					let val = $(this).html();
					let html = $('<input>', {
						type: 'text',
						name: 'nama',
						id: 'inline_ubah_value',
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
				$(this).on('select2:close', 'td.input_fakultas_id.clicked select', function(event) {
					event.preventDefault();
					$inline(this, 'fakultas_id');
				})

				$('[title]').tooltip()
				bsCustomFileInput.init()
			},
		})

		// ================================================== //
	}
</script>

<script>
	/**
	 * Keperluan CRUD
	 */
	// ================================================== //

	const $insert = () => {
		Swal.fire({
			title: 'Form Tambah Data',
			width: '800px',
			icon: 'info',
			html: `<?= $this->load->view("contents/$uri_segment/components/form_tambah", [], true); ?>`,
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
				$('.swal2-actions').css('z-index', '0')
				$('.select2-container').addClass('text-left')
				$('.select2-container--open').css('z-index', 9999)
				select2_in_form('tambah')
			},
			preConfirm: async () => {
				let formData = new FormData(document.getElementById('form_tambah'));

				let response = await axios.post(BASE_URL + 'insert', formData)
					.then(res => res.data.message)
					.catch(err => {
						let errors = err.response.data?.errors;
						if (errors && typeof errors === 'object') {
							Object.entries(errors).map(([key, value]) => {
								$(`#input_tambah_${key}`).addClass('is-invalid')
								$(`#error_tambah_${key}`).html(value).slideDown(500)
								$(`.select2-selection[aria-labelledby=select2-select_tambah_${key}-container]`).css('border-color', '#dc3545')
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
				}).then(() => {
					datatable.ajax.reload()
				})
			}
		})
	}

	const $update = (element) => {
		let row = datatable.row($(element).closest('tr')).data();

		Swal.fire({
			title: 'Form Ubah Data',
			width: '800px',
			icon: 'info',
			html: `<?= $this->load->view("contents/$uri_segment/components/form_ubah", [], true) ?>`,
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
				select2_in_form('ubah')
				$('.swal2-actions').css('z-index', '0')
				$('.select2-container').addClass('text-left')

				$('#input_ubah_nama').val(row.nama)
				$('#select_ubah_fakultas_id')
					.append(new Option(row.nama_fakultas, row.fakultas_id, true, true))
					.trigger('change')
			},
			preConfirm: async () => {
				let formData = new FormData(document.getElementById('form_ubah'));

				formData.append('id', row.id)

				let response = await axios.post(BASE_URL + 'update', formData)
					.then(res => res.data.message)
					.catch(err => {
						let errors = err.response.data?.errors;
						if (errors && typeof errors === 'object') {
							Object.entries(errors).map(([key, value]) => {
								$(`#input_ubah_${key}`).addClass('is-invalid')
								$(`#error_ubah_${key}`).html(value).slideDown(500)
								$(`.select2-selection[aria-labelledby=select2-select_ubah_${key}-container]`).css('border-color', '#dc3545')
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
				}).then(() => {
					datatable.ajax.reload()
				})
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
				formData.append('id', $(element).data('id'));

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

	const $inline = (element, name) => {
		$(element).closest(`td.input_${name}`).removeClass('clicked');
		let row = datatable.row($(element).closest('tr')).data();
		let val = $(element).val();

		let formData = new FormData();
		formData.append('id', row.id)
		formData.append('name', name)
		formData.append('value', val)

		axios.post(BASE_URL + 'inline', formData)
			.then(res => {
				let option = $(element).find('option:selected').html()
				if (option) $(element).closest(`td.input_${name}`).html(option)
				else $(element).closest(`td.input_${name}`).html(val)
				datatable.ajax.reload(null, false)
				toastr.success('Berhasil mengubah data', 'Sukses')
			})
			.catch(err => {
				let errors = err.response.data?.errors;
				if (errors && typeof errors === 'object') {
					toastr.error('Tidak boleh kosong', 'Gagal')
					Object.entries(errors).map(([key, value]) => {
						$(`#inline_ubah_${key}`).addClass('is-invalid')
					})
					$(element).focus()

					$(element).on('blur', (event) => {
						if ($(element).val()) $inline(element, name);
						else datatable.ajax.reload(null, false);
					})
				}
			})
	}
	// ================================================== //
</script>

<script>
	/**
	 * Keperluan input select2 didalam form
	 */
	// ================================================== //
	const select2_in_form = (status) => {
		$(`#form_${status} select#select_${status}_fakultas_id`).select2({
			placeholder: 'Pilih Fakultas',
			width: '100%',
			dropdownParent: $(`#swal2-html-container`),
			ajax: {
				url: BASE_URL + 'get_fakultas',
				dataType: 'JSON',
				delay: 250,
				data: function(params) {
					return {
						search: params.term, // search term
					};
				},
				processResults: function(response) {
					let myResults = [];
					response.data.map(item => {
						myResults.push({
							'id': item.id,
							'text': item.nama
						});
					})
					return {
						results: myResults
					};
				}
			}
		})
	}
	// ================================================== //
</script>