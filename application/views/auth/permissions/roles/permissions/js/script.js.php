<script>
	let datatable_role_permission, $role_permissions
	const BASE_URL = "<?= base_url($uri_segment) ?>"

	// Document ready
	$(() => {
		load_datatable_role_permission()

	})
</script>

<script>
	/**
	 * Keperluan DataTable, Datepicker, Summernote dan BsCustomFileInput
	 */
	// ================================================== //
	const load_datatable_role_permission = () => {
		datatable_role_permission = $('#datatable-role-permissions').DataTable({
			processing: true,
			destroy: true,
			ordering: false,
			paging: false,
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
			columnDefs: [{
				targets: [0, 1, 2, 3, 4], // Sesuaikan dengan jumlah kolom
				className: 'text-center'
			}, ],
		})
	}
</script>

<script>
	/**
	 * Keperluan CRUD
	 */
	// ================================================== //

	$role_permissions = async (event, id) => {
		event.preventDefault()

		Swal.fire({
			title: 'Apakah anda yakin?',
			text: "Pastikan permission yang dipilih sudah benar!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, sudah!',
			reverseButtons: true,
		}).then(async (result) => {
			if (result.isConfirmed && event.target.checkValidity()) {
				$('#role_permissions input[type=submit]').hide();
				$('#role_permissions button.loader').show();
				loading()

				let formData = new FormData(event.target);
				formData.append(
					await csrf().then(csrf => csrf.token_name),
					await csrf().then(csrf => csrf.hash)
				)

				axios.post(BASE_URL + 'role_permissions/' + id, formData)
					.then(res => {

						Swal.fire({
							icon: 'success',
							title: 'Success!',
							text: res.data.message,
							showConfirmButton: false,
							timer: 1500
						}).then(() => {
							// socket.emit('auth-crud-permission')
							location.reload()
						})

					}).catch(err => {
						console.error(err);
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							html: err.response.data.message,
							// text: err.response.statusText
						})
					}).then(() => {
						$('#role_permissions input[type=submit]').show();
						$('#role_permissions button.loader').hide();
						$('#role_permissions').trigger('reset');
						$('#role_permissions select').val(null).trigger('change')
						$('#role_permissions').removeClass('was-validated')
					})
			}
		})
	}
</script>
