<script>
	const BASE_URL = "<?= base_url($uri_segment) ?>"

	let $update_user = async (event, id) => {
		event.preventDefault()
		Swal.fire({
			title: 'Apakah anda yakin?',
			text: "Pastikan data yang terisi sudah benar!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, sudah!',
			reverseButtons: true
		}).then(async (result) => {
			if (result.isConfirmed && event.target.checkValidity()) {
				$('#update_user input[type=submit]').hide();
				$('#update_user button.loader').show();
				loading()

				let formData = new FormData(event.target);

				axios.post(BASE_URL + 'edit_user/' + id, formData)
					.then(res => {

						Swal.fire({
							icon: 'success',
							title: 'Success!',
							text: res.data.message,
							showConfirmButton: false,
							timer: 1500
						}).then(() => {
							location.href = BASE_URL
						})

					}).catch(err => {
						console.error(err);
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							html: err.response.statusText,
						})
					}).then(() => {
						$('#update_user input[type=submit]').show();
						$('#update_user button.loader').hide();
						$('#update_user').trigger('reset');
						$('#update_user select').val(null).trigger('change')
						$('#update_user').removeClass('was-validated')
					})
			}
		})
	}
</script>