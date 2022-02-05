<script>
	let datatable_user, $delete_user, $activate_user, $deactivate_user;
	const BASE_URL = "<?= base_url($uri_segment) ?>"

	// Document ready
	$(() => {
		load_datatable_user()
	})
</script>

<script>
	/**
	 * Keperluan DataTable, Datepicker, Summernote dan BsCustomFileInput
	 */
	// ================================================== //
	const load_datatable_user = () => {
		datatable_user = $('#datatable').DataTable({
			serverSide: true,
			processing: true,
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
					}).prop('outerHTML') + ' Tambah User', // Tambah Data
					action: (e, dt, node, config) => {
						location.href = BASE_URL + 'create_user'
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
				url: BASE_URL + 'data_user',
				type: 'POST',
				dataType: 'JSON',
			},
			columnDefs: [{
					targets: [0, 1, 2, 3, 4, 5], // Sesuaikan dengan jumlah kolom
					className: 'text-center'
				},
				{
					targets: [0, 5],
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
					title: 'Username',
					name: 'username',
					data: 'username',
				},
				{ // 2
					title: 'Email',
					name: 'email',
					data: 'email',
				},
				{ // 3
					title: 'Role',
					name: 'nama_role',
					data: 'nama_role',
					render: (nama_role) => {
						return $('<span>', {
							html: nama_role,
							class: 'badge badge-primary',
						}).prop('outerHTML')
					}
				},
				{ // 4
					title: 'Status',
					name: 'active',
					data: (data) => {
						return $('<a>', {
							html: eval(data.active) ? 'Active' : 'Inactive',
							class: eval(data.active) ? 'badge badge-success deactivate-user' : 'badge badge-danger activate-user',
							href: eval(data.active) ? BASE_URL + `deactivate/${data.id}` : BASE_URL + `activate/${data.id}`,
							'data-id': data.id
						}).prop('outerHTML')
					}
				},
				{ // 5
					title: 'Aksi',
					name: 'aksi',
					data: 'aksi',
				},
			],
			initComplete: function(event) {
				$(this).on('click', '.btn_edit', function(event) {
					event.preventDefault()
					location.href = BASE_URL + `edit_user/${$(this).data('id')}`
				});

				$(this).on('click', '.btn_delete', function(event) {
					event.preventDefault()
					$delete_user(this);
				});

				$(this).on('click', '.activate-user', function(event) {
					event.preventDefault()
					$activate_user(this)
				})

				$(this).on('click', '.deactivate-user', function(event) {
					event.preventDefault()
					$deactivate_user(this)
				})

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

	$delete_user = async (element) => {
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

				axios.post(BASE_URL + 'delete_user', formData)
					.then(res => {
						Swal.fire({
							icon: 'success',
							title: 'Success!',
							text: res.data.message,
							showConfirmButton: false,
							timer: 1500
						}).then(() => {
							datatable_user.ajax.reload()
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

	$activate_user = async (element) => {

		Swal.fire({
			title: 'Apakah anda yakin?',
			text: "Untuk mengaktifkan user ini",
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

				axios.post(BASE_URL + 'activate/' + $(element).data('id'), formData)
					.then(res => {
						Swal.fire({
							icon: 'success',
							title: 'Success!',
							text: res.data.message,
							showConfirmButton: false,
							timer: 1500
						}).then(() => {
							datatable_user.ajax.reload()
						})

					}).catch(err => {
						console.error(err);
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							// html: err.response.data.message,
							text: err.response.statusText
						})
					}).then(() => {
						$('#activate_user input[type=submit]').show();
						$('#activate_user button.loader').hide();
						$('#activate_user').trigger('reset');
						$('#activate_user select').val(null).trigger('change')
						$('#activate_user').removeClass('was-validated')
					})
			}
		})
	}

	$deactivate_user = async (element) => {
		Swal.fire({
			title: 'Apakah anda yakin?',
			text: "Untuk menonaktifkan user ini",
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
				formData.append('confirm', 'yes')

				axios.post(BASE_URL + 'deactivate/' + $(element).data('id'), formData)
					.then(res => {
						Swal.fire({
							icon: 'success',
							title: 'Success!',
							text: res.data.message,
							showConfirmButton: false,
							timer: 1500
						}).then(() => {
							// socket.emit('auth-deactivate-user', {})
							datatable_user.ajax.reload()
						})

					}).catch(err => {
						console.error(err);
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							// html: err.response.data.message,
							text: err.response.statusText
						})
					}).then(() => {
						$('#deactivate_user input[type=submit]').show();
						$('#deactivate_user button.loader').hide();
						$('#deactivate_user').trigger('reset');
						$('#deactivate_user select').val(null).trigger('change')
						$('#deactivate_user').removeClass('was-validated')
					})
			}
		})
	}
</script>