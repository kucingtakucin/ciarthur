<script>
	const BASE_URL = "<?= base_url($uri_segment) ?>"
	const BASE_URL_FOTO = "<?= base_url('backend/foto/') ?>"
	let [datatable_galeri, datatable_foto] = [null, null]

	// Document ready
	$(() => {
		load_datatable_galeri()
	})
</script>

<script>
	/**
	 * Keperluan DataTable, Datepicker, Summernote dan BsCustomFileInput
	 */
	// ================================================== //
	const load_datatable_galeri = () => {

		datatable_galeri = $('#datatable_galeri').DataTable({
			serverSide: true,
			processing: false,
			destroy: true,
			scrollX: true,
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
					action: (e, dt, node, config) => $insert_galeri()
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
			order: [],
			columns: [{ // 0
					title: '#',
					name: '#',
					data: 'no',
				},
				{ // 1
					title: 'Judul',
					name: 'judul',
					data: 'judul',
				},
				{ // 2
					title: 'Aktif',
					name: 'is_published',
					data: 'is_published',
				},
				{ // 3
					title: 'Aksi',
					name: 'aksi',
					data: 'aksi',
				},
			],
			initComplete: function(event) {
				$(this).on('dblclick', '.data-galeri', function(event) {
					event.preventDefault()
					$('a.nav-link[href="#foto-tab"]').removeClass('disabled').click()
					sessionStorage.setItem('uuid_galeri', $(this).data('uuid'))
					load_datatable_foto($(this).data('uuid'))
				})

				$(this).on('click', '.btn_edit', function(event) {
					event.preventDefault()
					$update_galeri(this);
				});

				$(this).on('click', '.btn_delete', function(event) {
					event.preventDefault()
					$delete_galeri(this);
				});

				$(this).on('click', '.activate-galeri', function(event) {
					event.preventDefault()
					$activate_galeri(this)
				})

				$(this).on('click', '.deactivate-galeri', function(event) {
					event.preventDefault()
					$deactivate_galeri(this)
				})

				bsCustomFileInput.init()
				$('[title]').tooltip()
			},
		})
	}

	const load_datatable_foto = (uuid_galeri) => {

		datatable_foto = $('#datatable_foto').DataTable({
			serverSide: true,
			processing: false,
			destroy: true,
			scrollX: true,
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
					action: (e, dt, node, config) => $insert_foto()
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
				url: BASE_URL_FOTO + 'data',
				type: 'POST',
				dataType: 'JSON',
				data: (d) => {
					d.uuid_galeri = uuid_galeri
				}
			},
			columnDefs: [{
					targets: [0, 4], // Sesuaikan dengan jumlah kolom
					className: 'text-center'
				},
				{
					targets: [0, 4],
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
					title: 'Gambar',
					name: 'gambar',
					data: 'gambar',
				},
				{ // 2
					title: 'Judul',
					name: 'judul',
					data: 'judul',
				},
				{ // 3
					title: 'Aktif',
					name: 'is_published',
					data: 'is_published',
				},
				{ // 4
					title: 'Aksi',
					name: 'aksi',
					data: 'aksi',
				},
			],
			initComplete: function(event) {
				$('a.nav-link[href="#galeri-tab"]').click(function(event) {
					$('a.nav-link[href="#foto-tab"]').addClass('disabled')
					sessionStorage.removeItem('uuid_galeri')
					datatable_foto.destroy()
				})

				$(this).on('click', '.btn_edit', function(event) {
					event.preventDefault()
					$update_foto(this);
				});

				$(this).on('click', '.btn_delete', function(event) {
					event.preventDefault()
					$delete_foto(this);
				});

				$(this).on('click', '.activate-foto', function(event) {
					event.preventDefault()
					$activate_foto(this)
				})

				$(this).on('click', '.deactivate-foto', function(event) {
					event.preventDefault()
					$deactivate_foto(this)
				})

				bsCustomFileInput.init()
				$('[title]').tooltip()
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

	const $insert_galeri = async () => {
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
			},
			preConfirm: async () => {
				let formData = new FormData(document.getElementById('form_tambah'));

				$('#form_tambah .invalid-feedback').fadeOut(500)
				$('#form_tambah .is-invalid').removeClass('is-invalid')
				let response = await axios.post(BASE_URL_FOTO + 'insert', formData)
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
					datatable_galeri.ajax.reload()
				})
			}
		})
	}

	const $update_galeri = async (element) => {
		let row = datatable_galeri.row($(element).closest('tr')).data();

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
				$('#form_ubah input#input_ubah_judul[name=judul]').val(row.judul_raw);
			},
			preConfirm: async () => {
				let formData = new FormData(document.getElementById('form_ubah'));
				formData.append('uuid', row.uuid)

				$('#form_ubah .invalid-feedback').fadeOut(500)
				$('#form_ubah .is-invalid').removeClass('is-invalid')
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
					datatable_galeri.ajax.reload()
				})
			}
		})
	}

	const $delete_galeri = async (element) => {
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

						datatable_galeri.ajax.reload()
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

	$activate_galeri = async (element) => {

		Swal.fire({
			title: 'Apakah anda yakin?',
			text: "Untuk mengaktifkan galeri ini",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Aktifkan',
			reverseButtons: true
		}).then(async (result) => {
			if (result.isConfirmed) {
				loading()

				let formData = new FormData();
				formData.append('uuid', $(element).data('uuid'))
				axios.post(BASE_URL + 'activate', formData)
					.then(res => {
						Swal.fire({
							icon: 'success',
							title: 'Success!',
							text: res.data.message,
							showConfirmButton: false,
							timer: 1500
						}).then(() => {
							datatable_galeri.ajax.reload()
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

	$deactivate_galeri = async (element) => {
		Swal.fire({
			title: 'Apakah anda yakin?',
			text: "Untuk menonaktifkan galeri ini",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Non-aktifkan',
			reverseButtons: true
		}).then(async (result) => {
			if (result.isConfirmed) {
				loading()

				let formData = new FormData();
				formData.append('uuid', $(element).data('uuid'))

				axios.post(BASE_URL + 'deactivate', formData)
					.then(res => {
						Swal.fire({
							icon: 'success',
							title: 'Success!',
							text: res.data.message,
							showConfirmButton: false,
							timer: 1500
						}).then(() => {
							// socket.emit('auth-deactivate-user', {})
							datatable_galeri.ajax.reload()
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

	// ================================================== //

	const $insert_foto = async (element) => {
		Swal.fire({
			title: 'Form Tambah Data',
			width: '800px',
			icon: 'info',
			html: `<?= $this->load->view("contents/$uri_segment_foto/components/form_tambah", '', true); ?>`,
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
			},
			preConfirm: async () => {
				let formData = new FormData(document.getElementById('form_tambah'));
				formData.append('uuid_galeri', sessionStorage.getItem('uuid_galeri'))

				$('#form_tambah .invalid-feedback').fadeOut(500)
				$('#form_tambah .is-invalid').removeClass('is-invalid')
				let response = await axios.post(BASE_URL_FOTO + 'insert', formData)
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
					datatable_foto.ajax.reload()
				})
			}
		})
	}

	const $update_foto = async (element) => {
		let row = datatable_foto.row($(element).closest('tr')).data();

		Swal.fire({
			title: 'Form Ubah Data',
			width: '800px',
			icon: 'info',
			html: `<?= $this->load->view("contents/$uri_segment_foto/components/form_ubah", '', true); ?>`,
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
				$('#form_ubah input#input_ubah_judul[name=judul]').val(row.judul_raw);
			},
			preConfirm: async () => {
				let formData = new FormData(document.getElementById('form_ubah'));
				formData.append('uuid', row.uuid)

				$('#form_ubah .invalid-feedback').fadeOut(500)
				$('#form_ubah .is-invalid').removeClass('is-invalid')
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
					datatable_foto.ajax.reload()
				})
			}
		})
	}

	const $delete_foto = async (element) => {
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

						datatable_foto.ajax.reload()
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

	$activate_foto = async (element) => {

		Swal.fire({
			title: 'Apakah anda yakin?',
			text: "Untuk mengaktifkan foto ini",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Aktifkan',
			reverseButtons: true
		}).then(async (result) => {
			if (result.isConfirmed) {
				loading()

				let formData = new FormData();
				formData.append('uuid', $(element).data('uuid'))
				axios.post(BASE_URL + 'activate', formData)
					.then(res => {
						Swal.fire({
							icon: 'success',
							title: 'Success!',
							text: res.data.message,
							showConfirmButton: false,
							timer: 1500
						}).then(() => {
							datatable_foto.ajax.reload()
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

	$deactivate_foto = async (element) => {
		Swal.fire({
			title: 'Apakah anda yakin?',
			text: "Untuk menonaktifkan foto ini",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Non-aktifkan',
			reverseButtons: true
		}).then(async (result) => {
			if (result.isConfirmed) {
				loading()

				let formData = new FormData();
				formData.append('uuid', $(element).data('uuid'))

				axios.post(BASE_URL + 'deactivate', formData)
					.then(res => {
						Swal.fire({
							icon: 'success',
							title: 'Success!',
							text: res.data.message,
							showConfirmButton: false,
							timer: 1500
						}).then(() => {
							// socket.emit('auth-deactivate-user', {})
							datatable_foto.ajax.reload()
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
	// ================================================== //
</script>

<script>
	$('#input_tambah_gambar').on('change', function() {
		$('#show-cover').show();

		if (this.files && this.files[0]) {
			let reader = new FileReader();

			reader.onload = (e) => {
				$('#show-cover').prop('src', e.target.result);
			};

			reader.readAsDataURL(this.files[0]);
		}
	});
</script>