<script>
	const BASE_URL = "<?= base_url($uri_segment) ?>"

	/**
	 * Keperluan CRUD
	 */
	// ================================================== //

	const $create_role = async (event) => {
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
				$('#create_role input[type=submit]').hide();
				$('#create_role button.loader').show();
				loading()

				let formData = new FormData(event.target);

				axios.post(BASE_URL + 'create_role', formData)
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
						$('#create_role input[type=submit]').show();
						$('#create_role button.loader').hide();
						$('#create_role').trigger('reset');
						$('#create_role select').val(null).trigger('change')
						$('#create_role').removeClass('was-validated')
					})
			}
		})
	}
</script>