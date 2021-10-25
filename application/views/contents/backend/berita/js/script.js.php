<script>
	const BASE_URL = "<?= base_url($uri_segment) ?>"
	let datatable, $insert, $update, $delete;

	// Document ready
	$(() => {
		/**
		 * Keperluan DataTable, Datepicker, Summernote dan BsCustomFileInput
		 */
		// ================================================== //
		datatable = $('#datatable').DataTable({
			serverSide: true,
			processing: true,
			destroy: true,
			// responsive: true,
			dom: `<"dt-custom-filter mb-3 d-block">
                <"d-flex flex-row justify-content-end flex-wrap mb-2"B>
                <"d-flex flex-row justify-content-between"lf>
                rt
                <"d-flex flex-row justify-content-between"ip>`,
			buttons: {
				/** Tombol-tombol Export & Tambah Data */
				buttons: [{
					className: 'btn btn-info m-2 text-white',
					text: $('<i>', {
						class: 'fa fa-plus'
					}).prop('outerHTML') + ' Tambah Data', // Tambah Data
					action: (e, dt, node, config) => {
						// $('#modal_tambah').modal('show');
						location.replace(BASE_URL + 'create')
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
				url: BASE_URL + 'data',
				type: 'GET',
				dataType: 'JSON',
				data: {},
				beforeSend: () => {
					loading()
				},
				complete: () => {
					setTimeout(async () => {
						await Swal.hideLoading()
						await Swal.close()
					}, 100);
				}
			},
			columnDefs: [{
					targets: [0, 1, 2, 3, 4], // Sesuaikan dengan jumlah kolom
					className: 'text-center'
				},
				{
					targets: [0, 1, 4],
					searchable: false,
					orderable: false,
				},
				{
					targets: [5],
					visible: false,
					searchable: false,
				}
			],
			order: [
				[5, 'desc']
			],
			columns: [{ // 0
					title: '#',
					name: '#',
					data: 'DT_RowIndex',
				},
				{ // 1
					title: 'Gambar',
					name: 'gambar',
					data: 'gambar',
					render: (gambar) => {
						return $('<img>', {
							src: `<?= base_url() ?>img/berita/${gambar}?w=100&h=200&fit=crop`,
							alt: 'Gambar'
						}).prop('outerHTML')
					}
				},
				{ // 2
					title: 'Judul',
					name: 'judul',
					data: 'judul',
				},
				{ // 3
					title: 'Kategori',
					name: 'nama_kategori',
					data: 'nama_kategori',
				},
				{ // 4
					title: 'Aksi',
					name: 'id',
					data: 'id',
					render: (id) => {
						let btn_edit = $('<button>', {
							type: 'button',
							class: 'btn btn-success btn_edit',
							'data-id': id,
							html: $('<i>', {
								class: 'fa fa-edit'
							}).prop('outerHTML'),
							title: 'Ubah Data'
						})

						let btn_delete = $('<button>', {
							type: 'button',
							class: 'btn btn-danger btn_delete',
							'data-id': id,
							html: $('<i>', {
								class: 'fa fa-trash'
							}).prop('outerHTML'),
							title: 'Hapus Data'
						})

						return $('<div>', {
							role: 'group',
							class: 'btn-group btn-group-sm',
							html: [btn_edit, btn_delete]
						}).prop('outerHTML')
					}
				},
				{ // 5
					title: 'Created At',
					name: 'created_at',
					data: 'created_at',
				}
			],
			initComplete: function(event) {
				$(this).on('click', '.btn_edit', function(event) {
					event.preventDefault()
					location.replace(BASE_URL + 'edit');
				});

				$(this).on('click', '.btn_delete', function(event) {
					event.preventDefault()
					$delete(this);
				});


			},
		})
		$('.datepicker').datepicker({
			format: 'yyyy-mm-dd',
			endDate: 'now',
			clearBtn: true,
			todayBtn: 'linked',
			autoclose: true
		})

		$('.summernote').summernote({
			height: 300, // set editor height
			minHeight: null, // set minimum height of editor
			maxHeight: null, // set maximum height of editor
			focus: true // set focus to editable area after initializin
		})

		tinymce.init({
			selector: '.tinymce',
			plugins: 'advlist lists anchor autolink autosave charmap code codesample directionality emoticons fullscreen help hr image importcss insertdatetime media nonbreaking noneditable pagebreak paste preview print save searchreplace link tabfocus table template wordcount',
			height: 420
		});

		bsCustomFileInput.init()

		datatable.on('draw.dt', function() {
			let PageInfo = datatable.page.info();
			datatable.column(0, {
				page: 'current'
			}).nodes().each(function(cell, i) {
				cell.innerHTML = i + 1 + PageInfo.start;
			});
		});


		// socket.on('backend-reload_dt-mahasiswa', () => {
		//     initMap()
		//     datatable.ajax.reload();
		// })
		// ================================================== //

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
				confirmButtonText: 'Ya, sudah!'
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
								datatable.ajax.reload()
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

		$update = async (form) => {
			loading()

			let formData = new FormData(form);
			formData.append(
				await csrf().then(csrf => csrf.token_name),
				await csrf().then(csrf => csrf.hash)
			)

			axios.post(BASE_URL + 'update', formData)
				.then(res => {
					$('#form_ubah button[type=submit]').hide();
					$('#form_ubah button.loader').show();

					Swal.fire({
						icon: 'success',
						title: 'Success!',
						text: res.data.message,
						showConfirmButton: false,
						timer: 1500
					}).then(() => {
						datatabke.ajax.reload()
					})

					// socket.emit('backend-crud-mahasiswa', {})
				}).catch(err => {
					console.error(err);
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						html: err.response.data.message,
						// text: err.response.statusText
					})
				}).then(() => {
					$('#form_ubah button[type=submit]').show();
					$('#form_ubah button.loader').hide();
					$('#form_ubah').trigger('reset');
					$('#form_ubah select').val(null).trigger('change')
					$('#form_ubah').removeClass('was-validated')
					$('#modal_ubah').modal('hide');
				})
		}

		$delete = async (element) => {
			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			}).then(async (result) => {
				if (result.isConfirmed) {
					loading()

					let formData = new FormData();
					formData.append('id', $(element).data('id'));
					formData.append(
						await csrf().then(csrf => csrf.token_name),
						await csrf().then(csrf => csrf.hash)
					)

					axios.post(BASE_URL + 'delete', formData)
						.then(res => {
							Swal.fire({
								icon: 'success',
								title: 'Success!',
								text: res.data.message,
								showConfirmButton: false,
								timer: 1500
							}).then(() => {
								datatable.ajax.reload()
							})

							// socket.emit('backend-crud-mahasiswa', {})
						}).catch(err => {
							console.error(err);
							Swal.fire({
								icon: 'error',
								title: 'Oops...',
								// html: err.response.data.message,
								text: err.response.statusText
							})
						})
				}
			})
		}

		// ================================================== //

		/**
		 * Keperluan event click tombol, reset, export, validasi dan submit form
		 */
		// ================================================== //
		$('#form_tambah').submit(function(event) {
			event.preventDefault()
			$insert(this);
		});

		$('#form_ubah').submit(function(event) {
			event.preventDefault();
			$update(this);
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
		// ================================================== //

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

		$('#form_ubah').ready(() => {
			ajax_select2_kategori_id('ubah')
			ajax_select2_tag('ubah')
		});

		// ================================================== //
	})
</script>
