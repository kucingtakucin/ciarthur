<script>
	const BASE_URL = "<?= base_url($uri_segment) ?>"

	// Document ready
	$(() => {
		/**
		 * Keperluan login
		 */
		// ================================================== //

		const $login = async (form) => {

			let formData = new FormData(form);

			$('.invalid-feedback').slideUp(500)
			$('.is-invalid').removeClass('is-invalid')
			axios.post(BASE_URL, formData)
				.then(res => {
					Swal.fire({
						icon: 'success',
						title: 'Success!',
						text: res.data.message,
						showConfirmButton: false,
						timer: 1500
					}).then(() => location.replace("<?= base_url() ?>" + res.data.redirect))
				}).catch(err => {
					if (!err?.response) _handle_csrf();

					let errors = err.response.data?.errors;
					let message = err.response.data?.message;
					let title = err.response.data?.title;

					if (errors && typeof errors === 'object') {
						Object.entries(errors).map(([key, value]) => {
							$(`#input_login_${key}`).addClass('is-invalid border-danger')
							$(`#error_login_${key}`).html(value).slideDown(500)
						})
						Swal.close();
					} else {
						Swal.fire({
							icon: 'error',
							title: title ? title : "Oops...",
							html: message ? message : err.response.statusText,
						})
					}
				}).then(res => grecaptcha.reset())
		}

		$('#form-login').submit(function(event) {
			event.preventDefault()
			$login(this);
		})

		// ================================================== //
	})
</script>