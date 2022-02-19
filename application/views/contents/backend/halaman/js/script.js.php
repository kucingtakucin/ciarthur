<script>
	const BASE_URL = "<?= base_url($uri_segment) ?>"
	let datatable, $insert, $update, $delete;

	// Document ready
	$(() => {
		load_datatable()
	});
</script>

<script>
	const load_datatable = () => {
		/**
		 * Keperluan DataTable, Datepicker, Summernote dan BsCustomFileInput
		 */
		// ================================================== //
		datatable = $('#datatable').DataTable({
			serverSide: true,
			processing: false,
			destroy: true,
			// responsive: true,
			dom: `<"dt-custom-filter mb-2 d-block">
                <"d-flex flex-row justify-content-end flex-wrap mb-2"B>
                <"d-flex flex-row justify-content-between mb-2"lf>
                rt
                <"d-flex flex-row justify-content-between"ip>`,
			buttons: {
				/** Tombol-tombol Export & Tambah Data */
				buttons: [{
					className: 'btn btn-info m-2 text-white',
					text: $('<i>', {
						class: 'fa fa-plus'
					}).prop('outerHTML') + ' Tambah Data', // Tambah Data
					action: (e, dt, node, config) => {
						// $('#modal_tambah').modal('show');
						location.href = (BASE_URL + 'insert')
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
				data: {},
			},
			columnDefs: [{
					targets: [0, 2, 3, 4], // Sesuaikan dengan jumlah kolom
					className: 'text-center'
				},
				{
					targets: [0, 3, 4],
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
					title: 'Kategori',
					name: 'nama_kategori',
					data: 'nama_kategori',
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
				$(this).on('click', '.btn_edit', function(event) {
					event.preventDefault()
					location.href = (BASE_URL + 'update/' + $(this).data('uuid'));
				});

				$(this).on('click', '.activate-halaman', function(event) {
					event.preventDefault()
					$activate_halaman(this)
				})

				$(this).on('click', '.deactivate-halaman', function(event) {
					event.preventDefault()
					$deactivate_halaman(this)
				})

				$(this).on('click', '.btn_delete', function(event) {
					event.preventDefault()
					$delete(this);
				});
			},
		})

		$('.datepicker').datepicker({
			format: 'yyyy-mm-dd',
			endDate: 'now',
			clearBtn: true,
			todayBtn: 'linked',
			autoclose: true
		})
		// ================================================== //
	}
</script>

<script>
	$delete = async (element) => {
		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
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
						}).then(() => {
							datatable.ajax.reload()
						})

						// socket.emit('backend-crud-mahasiswa', {})
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

	$activate_halaman = async (element) => {

		Swal.fire({
			title: 'Apakah anda yakin?',
			text: "Untuk mengaktifkan halaman ini",
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

	$deactivate_halaman = async (element) => {
		Swal.fire({
			title: 'Apakah anda yakin?',
			text: "Untuk menonaktifkan halaman ini",
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

	// ================================================== //
</script>