<script>
	const BASE_URL = "<?= base_url($uri_segment) ?>"
	let datatable, $insert, $update, $delete;

	// Document ready
	$(() => {
		load_datatable()
	});
</script>

<script>
	const load_datatable = () => {
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
						location.replace(BASE_URL + 'insert')
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
					location.replace(BASE_URL + 'update/' + $(this).data('id'));
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
	}
</script>

<script>
	$('#form_ubah').submit(function(event) {
		event.preventDefault();
		$update(this);
	});
	// ================================================== //
</script>

<script>
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
</script>
