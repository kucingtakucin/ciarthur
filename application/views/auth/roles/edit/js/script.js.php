<script>
	const BASE_URL = "<?= base_url($uri_segment) ?>"

	/**
	 * Keperluan CRUD
	 */
	// ================================================== //

	let $update_role = async (event, id) => {
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
				$('#update_role input[type=submit]').hide();
				$('#update_role button.loader').show();
				loading()

				let formData = new FormData(event.target);
				formData.append(
					await csrf().then(csrf => csrf.token_name),
					await csrf().then(csrf => csrf.hash)
				)

				axios.post(BASE_URL + 'edit_role/' + id, formData)
					.then(res => {

						Swal.fire({
							icon: 'success',
							title: 'Success!',
							text: res.data.message,
							showConfirmButton: false,
							timer: 1500
						}).then(() => {
							// socket.emit('auth-crud-role')
							location.replace(BASE_URL)
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
						$('#update_role input[type=submit]').show();
						$('#update_role button.loader').hide();
						$('#update_role').trigger('reset');
						$('#update_role select').val(null).trigger('change')
						$('#update_role').removeClass('was-validated')
					})
			}
		})
	}
</script>
