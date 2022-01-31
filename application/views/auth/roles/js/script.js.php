<script>
	let datatable_role, $delete_role;
	const BASE_URL = "<?= base_url($uri_segment) ?>"

	// Document ready
	$(() => {
		load_datatable_role()
	})
</script>

<script>
	/**
	 * Keperluan DataTable, Datepicker, Summernote dan BsCustomFileInput
	 */
	// ================================================== //
	const load_datatable_role = () => {
		datatable_role = $('#datatable').DataTable({
			serverSide: true,
			processing: true,
			destroy: true,
			// responsive: true,
			dom: `
					<"d-flex flex-row justify-content-end flex-wrap mb-2"B>
					<"d-flex flex-row justify-content-between"lf>
					rt
					<"d-flex flex-row justify-content-between"ip>`,
			buttons: {
				/** Tombol-tombol Export & Tambah Data */
				buttons: [{
					className: 'btn btn-info m-2 text-white',
					text: $('<i>', {
						class: 'fa fa-plus'
					}).prop('outerHTML') + ' Tambah Role', // Tambah Data
					action: (e, dt, node, config) => {
						location.replace(BASE_URL + 'create_role')
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
				url: BASE_URL + 'data_role',
				type: 'GET',
				dataType: 'JSON',
				data: {},
				beforeSend: () => {
					loading()
				},
				complete: () => {
					setTimeout(async () => {
						await Swal.hideLoading()
						await Swal.close()
					}, 100);
				}
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
				{
					targets: [4],
					visible: false,
					searchable: false,
				}
			],
			order: [
				[4, 'desc']
			],
			columns: [{ // 0
					title: '#',
					name: '#',
					data: 'DT_RowIndex',
				},
				{ // 1
					title: 'Role',
					name: 'name',
					data: 'name',
				},
				{ // 2
					title: 'Deskripsi',
					name: 'description',
					data: 'description',
				},
				{ // 3
					title: 'Aksi',
					name: 'encrypt_id',
					data: 'encrypt_id',
					render: (encrypt_id) => {
						let btn_edit = $('<button>', {
							type: 'button',
							class: 'btn btn-success btn_edit',
							'data-encrypt_id': encrypt_id,
							html: $('<i>', {
								class: 'fa fa-edit'
							}).prop('outerHTML'),
							title: 'Ubah Role'
						})

						let btn_delete = $('<button>', {
							type: 'button',
							class: 'btn btn-danger btn_delete',
							'data-encrypt_id': encrypt_id,
							html: $('<i>', {
								class: 'fa fa-trash'
							}).prop('outerHTML'),
							title: 'Hapus Role'
						})

						return $('<div>', {
							role: 'group',
							class: 'btn-group btn-group-sm',
							html: [btn_edit, btn_delete]
						}).prop('outerHTML')
					}
				},
				{ // 4
					title: 'Created At',
					name: 'created_at',
					data: 'created_at',
				}
			],
			initComplete: function(event) {
				$(this).on('click', '.btn_edit', function(event) {
					event.preventDefault()
					location.replace(BASE_URL + `edit_role/${$(this).data('encrypt_id')}`)
				});

				$(this).on('click', '.btn_delete', function(event) {
					event.preventDefault()
					$delete_role(this);
				});

				$('[title]').tooltip()
				// ================================================== //
			},
		})

		datatable_role.on('draw.dt', function() {
			let PageInfo = datatable_role.page.info();
			datatable_role.column(0, {
				page: 'current'
			}).nodes().each(function(cell, i) {
				cell.innerHTML = i + 1 + PageInfo.start;
			});
		});
	}

	// socket.on('auth-reload_dt-role', () => {
	//     datatable_role.ajax.reload();
	// })
</script>

<script>
	/**
	 * Keperluan CRUD
	 */
	// ================================================== //

	$delete_role = async (element) => {
		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!',
			reverseButtons: true
		}).then(async (result) => {
			if (result.isConfirmed) {
				loading()

				let formData = new FormData();
				formData.append('id', $(element).data('encrypt_id'));
				formData.append(
					await csrf().then(csrf => csrf.token_name),
					await csrf().then(csrf => csrf.hash)
				)

				axios.post(BASE_URL + 'delete_role', formData)
					.then(res => {
						Swal.fire({
							icon: 'success',
							title: 'Success!',
							text: res.data.message,
							showConfirmButton: false,
							timer: 1500
						}).then(() => {
							// socket.emit('auth-crud-role', {})
							datatable_role.ajax.reload()
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
