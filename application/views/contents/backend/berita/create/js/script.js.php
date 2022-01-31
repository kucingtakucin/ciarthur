<script>
	const BASE_URL = "<?= base_url($uri_segment) ?>"
	let $insert;

	$(() => {
		tinymce.init({
			selector: '.tinymce',
			plugins: 'advlist lists anchor autolink autosave charmap code codesample directionality emoticons fullscreen help hr image importcss insertdatetime media nonbreaking noneditable pagebreak paste preview print save searchreplace link tabfocus table template wordcount',
			height: 420
		});

		bsCustomFileInput.init()
	})
</script>

<script>
	/**
	 * Keperluan event click tombol, reset, export, validasi dan submit form
	 */
	// ================================================== //
	$('#form_tambah').submit(function(event) {
		event.preventDefault()
		$insert(this);
	});

	$('#input_tambah_gambar').on('change', function() {
		$('#show-cover').show();

		if (this.files && this.files[0]) {
			var reader = new FileReader();

			reader.onload = function(e) {
				$('#show-cover').attr('src', e.target.result);
			};

			reader.readAsDataURL(this.files[0]);
		}
	});

	$('#tambah_kategori').click(function(event) {
		event.preventDefault()
		Swal.fire({
			title: 'Form Tambah Data',
			width: '800px',
			icon: 'info',
			html: `<?= $this->load->view("contents/$uri_segment/components/form_kategori", '', true); ?>`,
			confirmButtonText: '<i class="fa fa-check-square-o"></i> Simpan Data',
			showCancelButton: true,
			focusConfirm: false,
			showLoaderOnConfirm: true,
			allowOutsideClick: false,
			allowEscapeKey: false,
			allowEnterKey: false,
			showCloseButton: true,
			reverseButtons: true,
			didOpen: () => {
				$('.swal2-actions').css('z-index', '0')
			},
			preConfirm: async () => {
				let formData = new FormData(document.getElementById('form_kategori'));

				formData.append(
					await csrf().then(csrf => csrf.token_name),
					await csrf().then(csrf => csrf.hash)
				)

				let response = await axios.post(BASE_URL + 'insert_kategori', formData)
					.then(res => res.data.message)
					.catch(err => {
						let errors = err.response.data.errors;
						if (typeof errors === 'object') {
							Object.entries(errors).map(([key, value]) => {
								$(`#input_tambah_${key}`).addClass('is-invalid')
								$(`#error_tambah_${key}`).html(value).fadeIn(500)
							})
						}
						Swal.showValidationMessage(err.response.data.message)
					})

				return {
					data: response
				}
			}
		}).then((result) => {
			if (result.value) {
				Swal.fire({
					title: 'Berhasil',
					icon: 'success',
					text: result.value.data,
					showConfirmButton: false,
					allowEscapeKey: false,
					allowOutsideClick: false,
					timer: 1500
				})
				// .then(() => {
				// 	datatable.ajax.reload()
				// })
			}
		})
	})
</script>

<script>
	/**
	 * Keperluan CRUD
	 */
	// ================================================== //

	$insert = async (form) => {
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
			if (result.isConfirmed) {
				loading()
				$('#form_tambah button[type=submit]').hide();
				$('#form_tambah button.loader').show();

				let formData = new FormData(form);
				formData.append(
					await csrf().then(csrf => csrf.token_name),
					await csrf().then(csrf => csrf.hash)
				)

				$('#form_tambah .invalid-feedback').fadeOut(500)
				$('#form_tambah .is-invalid').removeClass('is-invalid')
				axios.post(BASE_URL + 'insert', formData)
					.then(res => {
						Swal.fire({
							icon: 'success',
							title: 'Success!',
							text: res.data.message,
							showConfirmButton: false,
							timer: 1500
						}).then(() => {
							// datatable.ajax.reload()
							// location.replace(BASE_URL)
						})

						// socket.emit('backend-crud-mahasiswa', {})
					}).catch(err => {
						console.error(err);
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							html: err.response.data.message,
							showConfirmButton: false,
							timer: 1500
						}).then(() => {
							let errors = err.response.data.errors;
							if (typeof errors === 'object') {
								Object.entries(errors).map(([key, value]) => {
									$(`#input_tambah_${key}`).addClass('is-invalid')
									$(`#error_tambah_${key}`).html(value).fadeIn(500)
									$(`.select2-selection[aria-labelledby=select2-select_tambah_${key}-container]`).css('border-color', '#dc3545')
								})
							}
						})
					}).then(() => {
						$('#form_tambah button[type=submit]').show();
						$('#form_tambah button.loader').hide();
						$('#form_tambah').trigger('reset');
						$('#form_tambah select').val(null).trigger('change')
						$('#form_tambah').removeClass('was-validated')
						$('#modal_tambah').modal('hide');
					})
			}
		})
	}
</script>

<script>
	/**
	 * Keperluan input select2 didalam form
	 */
	// ================================================== //
	const ajax_select2_kategori_id = (status) => {
		$(`#form_${status} select#select_${status}_kategori_id`).select2({
			placeholder: 'Pilih Kategori',
			width: '100%',
			ajax: {
				url: BASE_URL + 'ajax_get_kategori',
				dataType: 'JSON',
				delay: 250,
				data: function(params) {
					return {
						search: params.term, // search term
						type: 'berita'
					};
				},
				processResults: function(response) {
					let myResults = [];
					response.data.map(item => {
						myResults.push({
							'id': item.id,
							'text': item.nama
						});
					})
					return {
						results: myResults
					};
				}
			}
		})
	}

	const ajax_select2_tag = (status) => {
		$(`#form_${status} select#select_${status}_tags`).select2({
			placeholder: 'Pilih Tag',
			tags: true,
			width: '100%',
			ajax: {
				url: BASE_URL + 'ajax_get_tags',
				dataType: 'JSON',
				delay: 250,
				data: function(params) {
					return {
						search: params.term, // search term
					};
				},
				processResults: function(response) {
					let myResults = [];
					response.data.map(item => {
						myResults.push({
							'id': item.id,
							'text': item.nama
						});
					})
					return {
						results: myResults
					};
				}
			}
		})
	}

	$('#form_tambah').ready(() => {
		ajax_select2_kategori_id('tambah')
		ajax_select2_tag('tambah')
	});

	// ================================================== //
</script>
