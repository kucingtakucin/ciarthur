<script>
	const BASE_URL = "<?= base_url($uri_segment) ?>"

	$(() => {
		tinymce.init({
			selector: '.tinymce',
			plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
			imagetools_cors_hosts: ['picsum.photos'],
			menubar: 'file edit view insert format tools table help',
			toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
			toolbar_sticky: true,
			autosave_ask_before_unload: true,
			autosave_interval: '30s',
			autosave_prefix: '{path}{query}-{id}-',
			autosave_restore_when_empty: false,
			autosave_retention: '2m',
			image_advtab: true,
			link_list: [{
					title: 'My page 1',
					value: 'https://www.tiny.cloud'
				},
				{
					title: 'My page 2',
					value: 'http://www.moxiecode.com'
				}
			],
			image_list: [{
					title: 'My page 1',
					value: 'https://www.tiny.cloud'
				},
				{
					title: 'My page 2',
					value: 'http://www.moxiecode.com'
				}
			],
			image_class_list: [{
					title: 'None',
					value: ''
				},
				{
					title: 'Some class',
					value: 'class-name'
				}
			],
			importcss_append: true,
			file_picker_callback: function(callback, value, meta) {
				/* Provide file and text for the link dialog */
				if (meta.filetype === 'file') {
					callback('https://www.google.com/logos/google.jpg', {
						text: 'My text'
					});
				}

				/* Provide image and alt text for the image dialog */
				if (meta.filetype === 'image') {
					callback('https://www.google.com/logos/google.jpg', {
						alt: 'My alt text'
					});
				}

				/* Provide alternative source and posted for the media dialog */
				if (meta.filetype === 'media') {
					callback('movie.mp4', {
						source2: 'alt.ogg',
						poster: 'https://www.google.com/logos/google.jpg'
					});
				}
			},
			templates: [{
					title: 'New Table',
					description: 'creates a new table',
					content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>'
				},
				{
					title: 'Starting my story',
					description: 'A cure for writers block',
					content: 'Once upon a time...'
				},
				{
					title: 'New list with dates',
					description: 'New List with dates',
					content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>'
				}
			],
			template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
			template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
			height: 600,
			image_caption: true,
			quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
			noneditable_noneditable_class: 'mceNonEditable',
			toolbar_mode: 'sliding',
			contextmenu: 'link image imagetools table',
			skin: "<?= session('dark_mode') ?>" === 'dark-only' ? 'oxide-dark' : 'oxide',
			content_css: "<?= session('dark_mode') ?>" === 'dark-only' ? 'dark' : 'default',
			content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
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
			let reader = new FileReader();

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
			}
		})
	})
</script>

<script>
	/**
	 * Keperluan CRUD
	 */
	// ================================================== //

	const $insert = async (form) => {
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

				let formData = new FormData(form)

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
							$('#form_tambah').trigger('reset');
							$('#form_tambah select').val(null).trigger('change')
							location.href = BASE_URL
						})

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
									$(`#input_tambah_${key.replace('[]','')}`).addClass('is-invalid')
									$(`#error_tambah_${key.replace('[]','')}`).html(value).fadeIn(500)
									$(`.select2-selection[aria-labelledby=select2-select_tambah_${key.replace('[]','')}-container]`).css('border-color', '#dc3545')
								})
							}
						})
					}).then(() => {
						$('#form_tambah button[type=submit]').show();
						$('#form_tambah button.loader').hide();
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