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
			processing: false,
			destroy: true,
			scrollX: true,
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
					}).prop('outerHTML') + ' Tambah Role', // Tambah Data
					action: (e, dt, node, config) => {
						location.href = BASE_URL + 'create_role'
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
					name: 'aksi',
					data: 'aksi',
				},
			],
			initComplete: function(event) {
				$(this).on('click', '.btn_edit', function(event) {
					event.preventDefault()
					location.replace(BASE_URL + `edit_role/${$(this).data('uuid')}`)
				});

				$(this).on('click', '.btn_delete', function(event) {
					event.preventDefault()
					$delete_role(this);
				});

				$('[title]').tooltip()
				// ================================================== //
			},
		})
	}
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
				formData.append('id', $(element).data('id'));

				axios.post(BASE_URL + 'delete_role', formData)
					.then(res => {
						Swal.fire({
							icon: 'success',
							title: 'Success!',
							text: res.data.message,
							showConfirmButton: false,
							timer: 1500
						}).then(() => {
							datatable_role.ajax.reload()
						})

					}).catch(err => {
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
</script>