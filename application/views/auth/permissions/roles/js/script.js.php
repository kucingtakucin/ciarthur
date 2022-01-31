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

	const load_datatable_permission = () => {
		datatable_permission = $('#datatable').DataTable({
			serverSide: true,
			processing: true,
			destroy: true,
			dom: `
                <"d-flex flex-row justify-content-end flex-wrap mb-2"B>
                <"d-flex flex-row justify-content-between"lf>
                rt
                <"d-flex flex-row justify-content-between"ip>`,
			buttons: {
				/** Tombol-tombol Export & Tambah Data */
				buttons: [{}, ],
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
				url: BASE_URL + 'data_roles',
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
					targets: [0, 1, 2], // Sesuaikan dengan jumlah kolom
					className: 'text-center'
				},
				{
					targets: [0, 2],
					searchable: false,
					orderable: false,
				},
				{
					targets: [3],
					visible: false,
					searchable: false,
				}
			],
			order: [
				[3, 'desc']
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
					render: (name) => {
						return $('<span>', {
							html: name.charAt(0).toUpperCase() + name.slice(1),
							class: 'badge badge-primary',
						}).prop('outerHTML')
					}
				},
				{ // 2
					title: 'Aksi',
					name: 'encrypt_id',
					data: 'encrypt_id',
					render: (encrypt_id) => {
						let btn_manage = $('<button>', {
							type: 'button',
							class: 'btn btn-dark text-white btn_manage',
							'data-id': encrypt_id,
							html: $('<i>', {
								class: 'fa fa-cog'
							}).prop('outerHTML'),
							title: 'Manage Permission'
						})

						return $('<div>', {
							role: 'group',
							class: 'btn-group btn-group-sm',
							html: [btn_manage]
						}).prop('outerHTML')
					}
				},
				{ // 3
					title: 'Created At',
					name: 'created_at',
					data: 'created_at',
				}
			],
			initComplete: function(event) {
				$(this).on('click', '.btn_manage', function(event) {
					event.preventDefault()
					location.replace(BASE_URL + `role_permissions/${$(this).data('id')}`)
				});

				$('[title]').tooltip()
				// ================================================== //
			},
		})

		datatable_permission.on('draw.dt', function() {
			let PageInfo = datatable_permission.page.info();
			datatable_permission.column(0, {
				page: 'current'
			}).nodes().each(function(cell, i) {
				cell.innerHTML = i + 1 + PageInfo.start;
			});
		});
	}

	// socket.on('auth-reload_dt-permission', () => {
	//     datatable_permission.ajax.reload();
	// })
	// ================================================== //
</script>
