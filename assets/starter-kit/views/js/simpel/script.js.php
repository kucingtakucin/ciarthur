<script>
	const BASE_URL = "<?= base_url($uri_segment) ?>"
	let datatable

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
			scrollX: true,
			dom: `<"dt-custom-filter mb-2 d-block">
                <"d-flex flex-row justify-content-end flex-wrap mb-2"B>
                <"d-flex flex-row justify-content-between mb-2"lf>
                rt
                <"d-flex flex-row justify-content-between mt-2"ip>`,
			buttons: {
				/** Tombol-tombol Export & Tambah Data */
				buttons: [{
					className: 'btn btn-info m-2 text-white',
					text: $('<i>', {
						class: 'fa fa-plus'
					}).prop('outerHTML') + ' Tambah Data', // Tambah Data
					action: (e, dt, node, config) => $('#modal_tambah').modal('show')
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
			},
			columnDefs: [{
					targets: [0, 1, 2, 3, 4, 5, 6, 7, 8], // Sesuaikan dengan jumlah kolom
					className: 'text-center'
				},
				{
					targets: [0, 1, 7, 8],
					searchable: false,
					orderable: false,
				},
			],
			order: [],
			columns: [{ // 0
					title: '#',
					name: '#',
					data: 'no',
				},
				{ // 1
					title: 'Foto',
					name: 'foto',
					data: 'foto',
					render: (foto) => {
						return $('<img>', {
							src: `<?= base_url() ?>img/apa/${foto}?w=100&h=200&fit=crop`,
							alt: 'Foto'
						}).prop('outerHTML')
					}
				},
				{ // 2
					title: 'Data_1',
					name: 'data_1',
					data: 'data_1',
				},
				{ // 3
					title: 'Data_2',
					name: 'data_2',
					data: 'data_2',
					render: (nama) => {
						return $('<span>', {
							html: nama,
							class: 'input_nama'
						}).prop('outerHTML')
					}
				},
				{ // 4
					title: 'Data_3',
					name: 'data_3',
					data: 'data_3',
				},
				{ // 5
					title: 'Data_4',
					name: 'data_4',
					data: 'data_4',
				},
				{ // 6
					title: 'Data_5',
					name: 'data_5',
					data: 'data_5',
				},
				{ // 7
					title: 'Data_6',
					name: 'data_6_7',
					data: (data) => {
						return $('<span>', {
								class: 'badge badge-primary',
								html: data.data_6 ? data.data_6 : '-'
							}).prop('outerHTML') + '<br>' +
							$('<span>', {
								class: 'badge badge-primary',
								html: data.data_7 ? data.data_7 : '-'
							}).prop('outerHTML')
					}
				},
				{ // 8
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
				{ // 9
					title: 'Created At',
					name: 'created_at',
					data: 'created_at',
				}
			],
			initComplete: function(event) {
				$(this).on('click', '.btn_edit', function(event) {
					event.preventDefault()
					$get(this);
				});

				$(this).on('click', '.btn_delete', function(event) {
					event.preventDefault()
					$delete(this);
				});

				/** Elemen - elemen filter */
				$('.dt-custom-filter').html((index, currentContent) => {

					// Filter tanggal
					let filter_tanggal = $('<div>', {
						class: 'col-md-4',
						html: [
							$('<label>', {
								for: 'filter_tanggal',
								html: 'Tanggal',
							}),
							$('<input>', {
								autocomplete: 'off',
								type: 'text',
								id: 'filter_tanggal',
								name: 'filter_tanggal',
								class: 'form-control datepicker',
								placeholder: 'Pilih Tanggal'
							})
						]
					})

					// Filter apa 1
					let filter_apa_1 = $('<div>', {
						class: 'col-md-4',
						html: [
							$('<label>', {
								for: 'filter_apa_1',
								html: 'Apa',
							}),
							$('<select>', {
								autocomplete: 'off',
								type: 'text',
								id: 'filter_apa_1',
								name: 'filter_apa_1',
								class: 'form-control js-select2',
								html: $('<option>', {
									html: ''
								})
							})
						]
					})

					// Filter apa 2
					let filter_apa_2 = $('<div>', {
						class: 'col-md-4',
						html: [
							$('<label>', {
								for: 'filter_apa_2',
								html: 'Apa',
							}),
							$('<select>', {
								autocomplete: 'off',
								type: 'text',
								id: 'filter_apa_2',
								name: 'filter_apa_2',
								class: 'form-control js-select2',
								html: $('<option>', {
									html: ''
								})
							})
						]
					})

					return $('<div>', {
						class: 'row',
						html: [filter_tanggal, filter_apa_1, filter_apa_2]
					}).prop('outerHTML')
				})

				/**
				 * Keperluan filter menggunakan select2
				 */
				// ================================================== //
				$('#filter_apa_1').select2({
					placeholder: 'Pilih Apa 1',
					width: '100%',
					ajax: {
						url: BASE_URL + "get_apa_1",
						dataType: 'JSON',
						delay: 250,
						data: (params) => {
							return {
								search: params.term, // search term
								page: params.page || 1
							};
						},
						processResults: (response, params) => {
							let myResults = [];
							let results = response.data
							results.map(item => {
								myResults.push({
									'id': item.id,
									'text': item.nama
								});
							})
							return {
								results: myResults,
							};
						}
					}
				}).on('select2:select', function(event) {
					$(`#filter_apa_2`).prop('disabled', false)
					datatable.column('nama_apa_1:name')
						.search(event.params.data.text)
						.draw()

					$(`#filter_apa_2`).select2({
						placeholder: 'Pilih Apa 2',
						width: '100%',
						ajax: {
							url: BASE_URL + "get_apa_2",
							dataType: 'JSON',
							delay: 250,
							data: function(params) {
								return {
									search: params.term, // search term
									apa_1_id: event.params.data.id,
									page: params.page || 1
								};
							},
							processResults: function(response, params) {
								let myResults = [];
								let results = response.data
								results.map(item => {
									myResults.push({
										'id': item.id,
										'apa_1_id': event
											.params.data.id,
										'text': item.nama
									});
								})

								return {
									results: myResults,
								};
							}
						}
					}).on('select2:select', function(event) {
						datatable.column('nama_apa_2:name')
							.search(event.params.data.text)
							.draw()
					})
				})
				// ================================================== //

				$('.datepicker').datepicker({
					format: 'yyyy-mm-dd',
					endDate: 'now',
					clearBtn: true,
					todayBtn: 'linked',
					autoclose: true
				})

				$('[title]').tooltip()
				bsCustomFileInput.init()
			},
		})

		datatable.on('draw.dt', function() {
			let PageInfo = datatable.page.info();
			datatable.column(0, {
				page: 'current'
			}).nodes().each(function(cell, i) {
				cell.innerHTML = i + 1 + PageInfo.start;
			});
		});

		// ================================================== //

		/**
		 * Keperluan CRUD
		 */
		// ================================================== //

		const $get = (element) => {
			let row = datatable.row($(element).closest('tr')).data();
			$('#modal_ubah').modal('show');
			$('#form_ubah input#id[name=id]').val(row.id)
			$('#form_ubah input#ubah_apa_1[name=apa_1]').val(row.apa_1);
			$('#form_ubah input#ubah_apa_2[name=apa_2]').val(row.apa_1);
			$('#form_ubah input#ubah_apa_3[name=apa_3]').val(row.apa_1);

			$('#form_ubah select#ubah_select_apa_1.select_apa_1')
				.append(new Option(row.nama_apa_1, row.apa_1_id, true, true))
				.trigger('change')
				.trigger({
					type: 'select2:select',
					params: {
						data: {
							id: row.apa_1_id,
							apa_1_id: row.apa_1_id,
							prodi_id: row.prodi_id
						}
					}
				})

			$('#form_ubah select#ubah_select_apa_2.select_apa_2')
				.append(new Option(row.nama_apa_2, row.apa_2_id, true, true))
				.trigger('change')
				.trigger({
					type: 'select2:select',
					params: {
						data: {
							apa_1_id: row.apa_1_id,
							apa_2_id: row.apa_2_id
						}
					}
				})

			$('#form_ubah input#ubah_old_foto[name=old_foto]').val(row.foto)
			$('#form_ubah input#ubah_old_foto_thumb[name=old_foto_thumb]').val(row.foto_thumb)
			if (row.foto) {
				$('#form_ubah #lihat').removeClass('text-danger')
				$('#form_ubah #lihat').addClass('text-success')
				$('#form_ubah #lihat').html(`<a href="<?= BASE_URL() ?>uploads/mahasiswa/${row.foto}" target="_blank">Lihat file</a>`)
			} else {
				$('#form_ubah #lihat').addClass('text-danger')
				$('#form_ubah #lihat').removeClass('text-success')
				$('#form_ubah #lihat').html('File belum ada')
			}
		}


		// ================================================== //

		/**
		 * Keperluan event click tombol, reset, export, validasi dan submit form
		 */
		// ================================================== //
		$('#form_tambah').submit(function(event) {
			event.preventDefault()
			if (this.checkValidity()) $insert(this);
		});

		$('#form_ubah').submit(function(event) {
			event.preventDefault();
			if (this.checkValidity()) $update(this);
		});

		$('#form_import').submit(function(event) {
			event.preventDefault()
			if (this.checkValidity()) $import(this);
		})

		$('#modal_tambah').on('hide.bs.modal', () => {
			$('#form_tambah').removeClass('was-validated')
			$('#form_tambah').trigger('reset')
		})

		$('#modal_ubah').on('hide.bs.modal', () => {
			$('#form_ubah').removeClass('was-validated')
			$('#form_ubah').trigger('reset')
		})
		// ================================================== //

		/**
		 * Keperluan input select2 didalam form
		 */
		// ================================================== //
		const select2_in_form = (status) => {
			$(`#form_${status} select#${status}_select_apa_1.select_apa_1`).select2({
				placeholder: 'Pilih apa_1',
				width: '100%',
				dropdownParent: $(`#modal_${status}`),
				ajax: {
					url: BASE_URL + 'get_apa_1',
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
			}).on('select2:select', function(event) {
				$(`#form_${status} select#${status}_select_apa_2.select_apa_2`).prop('disabled', false)
				$(`#form_${status} select#${status}_select_apa_2.select_apa_2`).select2({
					placeholder: 'Pilih Program Studi',
					width: '100%',
					dropdownParent: $(`#modal_${status}`),
					ajax: {
						url: BASE_URL + 'get_apa_2',
						dataType: 'JSON',
						delay: 250,
						data: function(params) {
							return {
								search: params.term, // search term
								apa_1_id: event.params.data.id
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
			})
		}

		$('#modal_tambah').on('show.bs.modal', () => select2_in_form('tambah'))
		$('#modal_ubah').on('show.bs.modal', () => select2_in_form('ubah'))
		// ================================================== //
	})
</script>
