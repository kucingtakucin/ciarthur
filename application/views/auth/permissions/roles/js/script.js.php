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
			processing: false,
			destroy: true,
			dom: `
                <"d-flex flex-row justify-content-end flex-wrap mb-2"B>
                <"d-flex flex-row mb-2 justify-content-between"lf>
                rt
                <"d-flex flex-row mt-2 justify-content-between"ip>`,
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
				type: 'POST',
				dataType: 'JSON',
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
			],
			order: [],
			columns: [{ // 0
					title: '#',
					name: '#',
					data: 'no',
				},
				{ // 1
					title: 'Role',
					name: 'name',
					data: 'name',
				},
				{ // 2
					title: 'Aksi',
					name: 'id',
					data: 'id',
					render: (id) => {
						let btn_manage = $('<button>', {
							type: 'button',
							class: 'btn btn-dark text-white btn_manage',
							'data-id': id,
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
			],
			initComplete: function(event) {
				$(this).on('click', '.btn_manage', function(event) {
					event.preventDefault()
					location.href = (BASE_URL + `role_permissions/${$(this).data('id')}`)
				});

				$('[title]').tooltip()
				// ================================================== //
			},
		})
	}

	// socket.on('auth-reload_dt-permission', () => {
	//     datatable_permission.ajax.reload();
	// })
	// ================================================== //
</script>