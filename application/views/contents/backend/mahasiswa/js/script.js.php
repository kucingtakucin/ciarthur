<script>
	const BASE_URL = "<?= base_url($uri_segment) ?>"
	let datatable, map, map_modal, marker_modal, legend;

	// Document ready
	$(() => {
		initMap() // Init leaflet map
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
						className: 'btn btn-primary m-2',
						text: $('<i>', {
							class: 'fa fa-file-word-o'
						}).prop('outerHTML') + ' Export DOCX', // Export DOCX
						action: (e, dt, node, config) => {
							// location.replace(BASE_URL + 'export_word');
							$export_word()
						}
					},
					{
						className: 'btn btn-danger m-2',
						text: $('<i>', {
							class: 'fa fa-file-pdf-o'
						}).prop('outerHTML') + ' Export PDF', // Export PDF
						action: (e, dt, node, config) => {
							// location.replace(BASE_URL + 'export_pdf');
							$export_pdf()
						}
					},
					{
						className: 'btn btn-success m-2',
						text: $('<i>', {
							class: 'fa fa-file-excel-o'
						}).prop('outerHTML') + ' Export XLSX', // Export XLSX
						action: (e, dt, node, config) => {
							// location.replace(BASE_URL + 'export_excel');
							$export_excel()
						}
					},
					{
						className: 'btn btn-success m-2 <?php if (is_denied('create-mahasiswa')) : ?>d-none<?php endif ?>',
						text: $('<i>', {
							class: 'fa fa-upload'
						}).prop('outerHTML') + ' Import XLSX', // Import XLSX
						action: (e, dt, node, config) => {
							$import_excel()
						}
					},
					{
						className: "btn btn-info m-2 text-white <?php if (is_denied('create-mahasiswa')) : ?>d-none<?php endif ?>",
						text: $('<i>', {
							class: 'fa fa-plus',
						}).prop('outerHTML') + ' Tambah Data', // Tambah Data
						action: (e, dt, node, config) => {
							// $('#modal_tambah').modal('show');
							$insert();
						}
					},
				],
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
					targets: [0, 8], // Sesuaikan dengan jumlah kolom
					className: 'text-center'
				},
				{
					targets: [0, 1, 7, 8],
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
					title: 'Foto',
					name: 'foto',
					data: 'foto',
				},
				{ // 2
					title: 'NIM',
					name: 'nim',
					data: 'nim',
					className: 'input_nim'
				},
				{ // 3
					title: 'Nama',
					name: 'nama',
					data: 'nama',
					className: 'input_nama'
				},
				{ // 4
					title: 'Program Studi',
					name: 'nama_prodi',
					data: 'nama_prodi',
					className: 'input_prodi_id'
				},
				{ // 5
					title: 'Fakultas',
					name: 'nama_fakultas',
					data: 'nama_fakultas',
					className: 'input_fakultas_id'
				},
				{ // 6
					title: 'Angkatan',
					name: 'angkatan',
					data: 'angkatan',
					className: 'input_angkatan',
				},
				{ // 7
					title: 'LatLng',
					name: 'latlng',
					data: 'latlng',
				},
				{ // 8
					title: 'Aksi',
					name: 'aksi',
					data: 'aksi'
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
							url: BASE_URL + 'ajax_get_fakultas',
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

				$(this).on('dblclick', 'td.input_prodi_id:not(.clicked)', function(event) {
					event.preventDefault()
					$(this).addClass('clicked')
					let row = datatable.row($(this).closest('tr')).data();

					let val = $(this).html();
					let html = $('<select>', {
						name: 'prodi_id',
						id: 'inline_ubah_value',
						class: 'form-control',
						html: $('<option>', {
							html: ''
						})
					}).prop('outerHTML')

					$(this).html(html)
					$(this).find('select').select2({
						placeholder: 'Pilih Program Studi',
						width: '100%',
						ajax: {
							url: BASE_URL + 'ajax_get_prodi',
							dataType: 'JSON',
							delay: 250,
							data: function(params) {
								return {
									search: params.term, // search term
									fakultas_id: row.fakultas_id
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
						.append(new Option(row.nama_prodi, row.prodi_id, true, true))
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

				$(this).on('dblclick', 'td.input_nim:not(.clicked)', function(event) {
					event.preventDefault()
					$(this).addClass('clicked')

					let val = $(this).html();
					let html = $('<input>', {
						type: 'text',
						name: 'nim',
						id: 'inline_ubah_value',
						class: 'form-control',
						autocomplete: 'off',
						value: val
					}).prop('outerHTML')

					$(this).html(html)
					$(this).find('input').focus()
				})

				$(this).on('dblclick', 'td.input_angkatan:not(.clicked)', function(event) {
					event.preventDefault()
					$(this).addClass('clicked')

					let val = $(this).html();
					let html = $('<input>', {
						type: 'text',
						name: 'angkatan',
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

				$(this).on('blur', 'td.input_nim.clicked input', function(event) {
					event.preventDefault();
					$inline(this, 'nim');
				})

				$(this).on('blur', 'td.input_angkatan.clicked input', function(event) {
					event.preventDefault();
					$inline(this, 'angkatan');
				})

				$(this).on('select2:close', 'td.input_fakultas_id.clicked select', function(event) {
					event.preventDefault();
					$inline(this, 'fakultas_id');
				})

				$(this).on('select2:close', 'td.input_prodi_id.clicked select', function(event) {
					event.preventDefault();
					$inline(this, 'prodi_id');
				})

				/** Elemen - elemen filter */
				$('.dt-custom-filter').html((index, currentContent) => {
					// Filter tanggal
					let filter_tanggal = $('<div>', {
						class: 'col-md-4',
						html: [
							$('<label>', {
								for: 'filter_tanggal',
								html: 'Tanggal',
							}),
							$('<input>', {
								autocomplete: 'off',
								type: 'text',
								id: 'filter_tanggal',
								name: 'filter_tanggal',
								class: 'form-control datepicker-here',
								placeholder: 'Pilih Tanggal'
							})
						]
					})

					// Filter fakultas
					let filter_fakultas = $('<div>', {
						class: 'col-md-4',
						html: [
							$('<label>', {
								for: 'filter_fakultas',
								html: 'Fakultas',
							}),
							$('<select>', {
								autocomplete: 'off',
								type: 'text',
								id: 'filter_fakultas',
								name: 'filter_fakultas',
								class: 'form-control js-select2',
								html: $('<option>', {
									html: ''
								})
							})
						]
					})

					// Filter prodi
					let filter_prodi = $('<div>', {
						class: 'col-md-4',
						html: [
							$('<label>', {
								for: 'filter_prodi',
								html: 'Prodi',
							}),
							$('<select>', {
								autocomplete: 'off',
								type: 'text',
								id: 'filter_prodi',
								name: 'filter_prodi',
								class: 'form-control js-select2',
								disabled: true,
								html: $('<option>', {
									html: ''
								})
							})
						]
					})

					return $('<div>', {
						class: 'row',
						html: [filter_tanggal, filter_fakultas, filter_prodi]
					}).prop('outerHTML')
				})

				/**
				 * Keperluan filter menggunakan select2
				 */
				// ================================================== //
				$('#filter_fakultas').select2({
					placeholder: 'Pilih Fakultas',
					width: '100%',
					ajax: {
						url: BASE_URL + "ajax_get_fakultas",
						dataType: 'JSON',
						delay: 250,
						data: function(params) {
							return {
								search: params.term, // search term
								page: params.page || 1
							};
						},
						processResults: function(response, params) {
							let myResults = [];
							let results = response.data
							results.map(item => {
								myResults.push({
									'id': item.id,
									'text': item.nama
								});
							})
							return {
								results: myResults,
							};
						}
					}
				}).on('select2:select', function(event) {
					$(`#filter_prodi`).prop('disabled', false)
					datatable.column('nama_fakultas:name')
						.search(event.params.data.text)
						.draw()

					$(`#filter_prodi`).select2({
						placeholder: 'Pilih Program Studi',
						width: '100%',
						ajax: {
							url: BASE_URL + "ajax_get_prodi",
							dataType: 'JSON',
							delay: 250,
							data: function(params) {
								return {
									search: params.term, // search term
									fakultas_id: event.params.data.id,
									page: params.page || 1
								};
							},
							processResults: function(response, params) {
								let myResults = [];
								let results = response.data
								results.map(item => {
									myResults.push({
										'id': item.id,
										'fakultas_id': event
											.params.data.id,
										'text': item.nama
									});
								})

								return {
									results: myResults,
								};
							}
						}
					}).on('select2:select', function(event) {
						datatable.column('nama_prodi:name')
							.search(event.params.data.text)
							.draw()
					})
				})
				// ================================================== //

				$('.datepicker-here').datepicker({
					dateFormat: 'yyyy-mm-dd',
					todayButton: true,
					clearButton: false,
					autoClose: true,
					language: 'en',
				})

				bsCustomFileInput.init()
				$('[title]').tooltip()
			},
		})
	}
</script>

<script>
	/**
	 * Keperluan CRUD
	 */
	// ================================================== //

	const $insert = async () => {
		Swal.fire({
			title: 'Form Tambah Data',
			width: '800px',
			icon: 'info',
			html: `<?= $this->load->view("contents/$uri_segment/components/form_tambah", '', true); ?>`,
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
				select2_in_swal('tambah')
				map_in_swal('tambah')
				bsCustomFileInput.init()
			},
			preConfirm: async () => {
				let formData = new FormData(document.getElementById('form_tambah'));

				$('#form_tambah .invalid-feedback').fadeOut(500)
				$('#form_tambah .is-invalid').removeClass('is-invalid')
				let response = await axios.post(BASE_URL + 'insert', formData)
					.then(res => res.data.message)
					.catch(err => {
						let errors = err.response.data?.errors;
						if (errors && typeof errors === 'object') {
							Object.entries(errors).map(([key, value]) => {
								$(`#input_tambah_${key}`).addClass('is-invalid')
								$(`#error_tambah_${key}`).html(value).fadeIn(500)
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
					bsCustomFileInput.destroy()
					datatable.ajax.reload()
				})
			}
		})
	}

	const $update = async (element) => {
		let row = datatable.row($(element).closest('tr')).data();

		Swal.fire({
			title: 'Form Ubah Data',
			width: '800px',
			icon: 'info',
			html: `<?= $this->load->view("contents/$uri_segment/components/form_ubah", '', true); ?>`,
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
				select2_in_swal('ubah')
				map_in_swal('ubah')

				$('#form_ubah input#input_ubah_nim[name=nim]').val(row.nim);
				$('#form_ubah input#input_ubah_nama[name=nama]').val(row.nama);
				$('#form_ubah input#input_ubah_angkatan[name=angkatan]').val(row.angkatan);
				$('#form_ubah input#input_ubah_latitude[name=latitude]').val(row.latitude);
				$('#form_ubah input#input_ubah_longitude[name=longitude]').val(row.longitude);

				$('#form_ubah select#select_ubah_fakultas_id')
					.append(new Option(row.nama_fakultas, row.fakultas_id, true, true))
					.trigger('change')
					.trigger({
						type: 'select2:select',
						params: {
							data: {
								id: row.fakultas_id,
								fakultas_id: row.fakultas_id,
								prodi_id: row.prodi_id
							}
						}
					})

				$('#form_ubah select#select_ubah_prodi_id')
					.append(new Option(row.nama_prodi, row.prodi_id, true, true))
					.trigger('change')
					.trigger({
						type: 'select2:select',
						params: {
							data: {
								fakultas_id: row.fakultas_id,
								prodi_id: row.prodi_id
							}
						}
					})

				bsCustomFileInput.init()
			},
			preConfirm: async () => {
				let formData = new FormData(document.getElementById('form_ubah'));
				formData.append('uuid', row.uuid)
				formData.append('old_foto', row.foto)

				$('#form_tambah .invalid-feedback').fadeOut(500)
				$('#form_tambah .is-invalid').removeClass('is-invalid')
				let response = await axios.post(BASE_URL + 'update', formData)
					.then(res => res.data.message)
					.catch(err => {
						let errors = err.response.data?.errors;
						if (errors && typeof errors === 'object') {
							Object.entries(errors).map(([key, value]) => {
								$(`#input_ubah_${key}`).addClass('is-invalid')
								$(`#error_ubah_${key}`).html(value).fadeIn(500)
								$(`.select2-selection[aria-labelledby=select2-select_ubah_${key}-container]`).css('border-color', '#dc3545')
							})
						}
						Swal.showValidationMessage(err.response.data?.message && err.response.statusText)
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
					bsCustomFileInput.destroy()
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
				formData.append('uuid', $(element).data('uuid'));
				axios.post(BASE_URL + 'delete', formData)
					.then(res => {
						Swal.fire({
							icon: 'success',
							title: 'Success!',
							text: res.data.message,
							showConfirmButton: false,
							timer: 1500
						})

						// socket.emit('backend-crud-mahasiswa', {})
						datatable.ajax.reload()
					}).catch(err => {
						console.error(err);
						Swal.fire({
							icon: 'error',
							title: err.response.statusText,
							html: err.response.data.message,
							// text: err.response.
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
		formData.append('uuid', row.uuid)
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

	const $import_excel = async () => {
		Swal.fire({
			title: 'Form Import Excel',
			width: '800px',
			icon: 'info',
			html: `<?= $this->load->view("contents/$uri_segment/components/form_import", '', true); ?>`,
			confirmButtonText: '<i class="fa fa-check-square-o"></i> Import File',
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
				bsCustomFileInput.init()

				$('#form_import #downloadTemplateExcel').click(async () => {
					$('.tombol-download-template').hide()
					$('.loader').show()

					let formData = new FormData();

					axios.post(BASE_URL + 'download_template_excel', formData, {
							responseType: 'blob'
						})
						.then(blob => {
							$('.tombol-download-template').show()
							$('.loader').hide()

							const url = window.URL.createObjectURL(new Blob([blob.data]));
							const a = document.createElement('a');
							a.style.display = 'none';
							a.href = url;
							a.download = blob.headers.filename;
							document.body.appendChild(a);
							a.click();
							window.URL.revokeObjectURL(url);
						}).catch(err => {
							console.error(err);
						})
				})

			},
			preConfirm: async () => {
				let formData = new FormData(document.getElementById('form_import'));

				let response = await axios.post(BASE_URL + 'import_excel', formData)
					.then(res => res.data.message)
					.catch(err => {
						let errors = err.response.data.errors;

						Swal.showValidationMessage(err.response.data.message)
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
					bsCustomFileInput.destroy()
					datatable.ajax.reload()
				})
			}
		})
	}

	const $export_excel = async () => {
		loading()

		let formData = new FormData();

		axios.post(BASE_URL + 'export_excel', formData, {
				responseType: 'blob'
			})
			.then(blob => {
				const url = window.URL.createObjectURL(new Blob([blob.data]));
				const a = document.createElement('a');
				a.style.display = 'none';
				a.href = url;
				a.download = blob.headers.filename;
				document.body.appendChild(a);
				a.click();
				window.URL.revokeObjectURL(url);
				Swal.fire({
					icon: 'success',
					title: 'Success!',
					text: "Berhasil melakukan export",
					showConfirmButton: false,
					timer: 1500
				})
			}).catch(err => {
				console.error(err);
				Swal.fire({
					icon: 'error',
					title: err.response.statusText,
					html: err.response.data.message,
					// text: err.response.statusText,
				})
			})
	}

	const $export_pdf = async () => {
		loading()

		let formData = new FormData();

		axios.post(BASE_URL + 'export_pdf', formData, {
				responseType: 'blob'
			})
			.then(blob => {
				const url = window.URL.createObjectURL(new Blob([blob.data]));
				const a = document.createElement('a');
				a.style.display = 'none';
				a.href = url;
				a.download = blob.headers.filename;
				document.body.appendChild(a);
				a.click();
				window.URL.revokeObjectURL(url);
				Swal.fire({
					icon: 'success',
					title: 'Success!',
					text: "Berhasil melakukan export",
					showConfirmButton: false,
					timer: 1500
				})
			}).catch(err => {
				console.error(err);
				Swal.fire({
					icon: 'error',
					title: err.response.statusText,
					html: err.response.data.message,
					// text: err.response.statusText,
				})
			})
	}

	const $export_word = async () => {
		loading()

		let formData = new FormData();

		axios.post(BASE_URL + 'export_docx', formData, {
				responseType: 'blob'
			})
			.then(blob => {
				const url = window.URL.createObjectURL(new Blob([blob.data]));
				const a = document.createElement('a');
				a.style.display = 'none';
				a.href = url;
				a.download = blob.headers.filename;
				document.body.appendChild(a);
				a.click();
				window.URL.revokeObjectURL(url);
				Swal.fire({
					icon: 'success',
					title: 'Success!',
					text: "Berhasil melakukan export",
					showConfirmButton: false,
					timer: 1500
				})
			}).catch(err => {
				console.error(err);
				Swal.fire({
					icon: 'error',
					title: err.response.statusText,
					html: err.response.data.message,
					// text: err.response.statusText,
				})
			})
	}
	// ================================================== //
</script>

<script>
	/**
	 * Keperluan input select2 didalam form
	 */
	// ================================================== //
	const select2_in_swal = (status) => {
		$(`#form_${status} select#select_${status}_fakultas_id`).select2({
			placeholder: 'Pilih Fakultas',
			width: '100%',
			dropdownParent: $(`#swal2-html-container`),
			ajax: {
				url: BASE_URL + 'ajax_get_fakultas',
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
		}).on('select2:select', function(event) {
			$(`#form_${status} #select_${status}_prodi_id`).prop('disabled', false)
			$(`#form_${status} #select_${status}_prodi_id`).select2({
				placeholder: 'Pilih Program Studi',
				width: '100%',
				dropdownParent: $(`#swal2-html-container`),
				ajax: {
					url: BASE_URL + 'ajax_get_prodi',
					dataType: 'JSON',
					delay: 250,
					data: function(params) {
						return {
							search: params.term, // search term
							fakultas_id: event.params.data.id
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
		})
	}
	// ================================================== //
</script>

<script>
	/**
	 * Keperluan WebGIS dengan Leaflet
	 * 
	 */
	// ================================================== //

	const initMap = () => {
		if (map) map.remove()
		map = L.map("map", {
			center: [-7.5828, 111.0444],
			zoom: 12,
			layers: [
				/** OpenStreetMap Tile Layer */
				L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
					attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
				}),
			],
			scrollWheelZoom: false,
		})

		/** Legend */
		legend = L.control({
			position: "bottomleft"
		})

		legend.onAdd = (map) => {
			let div = L.DomUtil.create("div", "legend");
			div.innerHTML += "<h3><b>KABUPATEN KARANGANYAR</b></h3>";
			return div;
		}

		legend.addTo(map)

		/** GeoJSON Features */
		$.getJSON(BASE_URL + 'ajax_get_geojson',
			response => {
				let geojson = L.geoJSON(response, {
					onEachFeature: (feature, layer) => {
						layer.on({
							mouseover: (event) => {
								let layer = event.target;
								layer.setStyle({
									weight: 5,
									dashArray: '',
									fillOpacity: 0.7
								});
								if (!L.Browser.ie && !L.Browser.opera &&
									!L.Browser.edge) {
									layer.bringToFront();
								}
							},
							mouseout: (event) => {
								geojson.resetStyle(event.target)
							},
							click: (event) => {
								map.fitBounds(event
									.target
									.getBounds()
								);
							}
						})
					}
				}).addTo(map)
			})

		axios.get(BASE_URL + 'ajax_get_kecamatan')
			.then(res => {
				let results = res.data.data
				results.map(item => {
					if (item.latitude && item.longitude) {
						L.marker([item.latitude, item.longitude])
							.addTo(map)
							.bindPopup(
								new L.Popup({
									autoClose: false,
									closeOnClick: false
								})
								.setContent(`<b>${item.nama}</b>`)
								.setLatLng([item.latitude, item.longitude])
							).openPopup();
					}
				})
			})

		axios.get(BASE_URL + 'ajax_get_latlng')
			.then(res => {
				let results = res.data.data
				results.map(item => {
					if (item.latitude && item.longitude) {
						L.marker([item.latitude, item.longitude], {
								icon: L.icon({
									iconUrl: 'https://upload.wikimedia.org/wikipedia/commons/f/f2/678111-map-marker-512.png',
									iconSize: [
										40, 40
									], // size of the icon
									iconAnchor: [
										20, 40
									], // point of the icon which will correspond to marker's location
									popupAnchor: [
										0, -30
									] // point from which the popup should open relative to the iconAnchor
								})
							})
							.addTo(map)
							.bindPopup(
								new L.Popup({
									autoClose: false,
									closeOnClick: false
								})
								.setContent(`<b>${item.nama}</b>`)
								.setLatLng([
									item.latitude, item.longitude
								])
							).openPopup();
					}
				})
			})
	}

	const map_in_swal = (status) => {

		if (map_modal) map_modal.remove()
		map_modal = L.map(`map-${status}`, {
			center: [-7.5828, 111.0444],
			zoom: 12,
			layers: [
				/** OpenStreetMap Tile Layer */
				L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
					attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
				}),

			],
			scrollWheelZoom: false,
		})

		setTimeout(() => {
			map_modal.invalidateSize()
		}, 500);

		map_modal.on('click', (event) => {
			if (marker_modal) map_modal.removeLayer(marker_modal)
			marker_modal = L.marker([event.latlng.lat, event.latlng
				.lng
			], { //-7.641355, 111.0377783
				icon: L.icon({
					iconUrl: 'https://upload.wikimedia.org/wikipedia/commons/f/f2/678111-map-marker-512.png',
					iconSize: [40, 40], // size of the icon
					iconAnchor: [
						20, 40
					], // point of the icon which will correspond to marker's location
					popupAnchor: [
						0, -30
					] // point from which the popup should open relative to the iconAnchor
				})
			})
			marker_modal.addTo(map_modal)
			marker_modal.bindPopup(`${event.latlng.lat}, ${event.latlng.lng}`).openPopup()

			$(`#input_${status}_latitude`).val(event.latlng.lat)
			$(`#input_${status}_longitude`).val(event.latlng.lng)
		})
	}
</script>