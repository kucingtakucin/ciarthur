<script>
	const BASE_URL = "<?= base_url($uri_segment) ?>"

	/**
	 * Keperluan CRUD
	 */
	// ================================================== //

	let $create_user = async (event) => {
		event.preventDefault()
		Swal.fire({
			title: 'Apakah anda yakin?',
			text: "Pastikan data yang terisi sudah benar!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, sudah!',
			reverseButtons: true,
		}).then(async (result) => {
			if (result.isConfirmed && event.target.checkValidity()) {
				$('#create_user input[type=submit]').hide();
				$('#create_user button.loader').show();
				loading()

				let formData = new FormData(event.target);
				formData.append(
					await csrf().then(csrf => csrf.token_name),
					await csrf().then(csrf => csrf.hash)
				)

				axios.post(BASE_URL + 'create_user', formData)
					.then(res => {
						Swal.fire({
							icon: 'success',
							title: 'Success!',
							text: res.data.message,
							showConfirmButton: false,
							timer: 1500
						}).then(() => {
							// socket.emit('auth-crud-user', {})
							datatable_user.ajax.reload()
							location.replace(BASE_URL)
						})

					}).catch(err => {
						console.error(err);
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							html: err.response.data.message,
							// text: err.response.statusText,
						})
					}).then(() => {
						$('#create_user input[type=submit]').show();
						$('#create_user button.loader').hide();
						$('#create_user').trigger('reset');
						$('#create_user').removeClass('was-validated')
						$('#create_user select').val(null).trigger('change')
					})
			}
		})
	}
</script>
