<script>
	const BASE_URL = "<?= base_url($uri_segment) ?>"
	let datatable, $get, $chat

	$(() => {
		/**
		 * Keperluan DataTable, Datepicker, Summernote dan BsCustomFileInput
		 */
		// ================================================== //
		datatable = $('#datatable').DataTable({
			serverSide: true,
			processing: false,
			destroy: true,
			scrollX: true,
			dom: `<"dt-custom-filter mb-2 d-block">
                <"d-flex flex-row justify-content-end flex-wrap mb-2"B>
                <"d-flex flex-row justify-content-between mb-2"lf>
                rt
                <"d-flex flex-row justify-content-between mt-2"ip>`,
			ajax: {
				url: BASE_URL + 'data',
				type: 'POST',
				dataType: 'JSON',
			},
			columnDefs: [{
					targets: [0, 1, 2, 3, 4, 5], // Sesuaikan dengan jumlah kolom
					className: 'text-center'
				},
				{
					targets: [0, 5],
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
					title: 'Nama',
					name: 'name',
					data: 'name',
				},
				{ // 2
					title: 'Email',
					name: 'email',
					data: 'email',
				},
				{ // 3
					title: 'Phone',
					name: 'phone',
					data: 'phone',
				},
				{ // 4
					title: 'Pesan',
					name: 'message',
					data: 'message',
				},
				{ // 5
					title: 'Aksi',
					name: 'aksi',
					data: 'aksi',
				},
			],
			initComplete: function(event) {
				$(this).on('click', '.btn_detail', function(event) {
					event.preventDefault()
					$get(this);
				});

				$(this).on('click', '.btn_chat', function(event) {
					event.preventDefault()
					$chat(this);
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
								class: 'form-control datepicker-here',
								placeholder: 'Pilih Tanggal'
							})
						]
					})

					return $('<div>', {
						class: 'row',
						html: [filter_tanggal]
					}).prop('outerHTML')
				})

				// ================================================== //

				$('.datepicker-here').datepicker({
					dateFormat: 'yyyy-mm-dd',
					todayButton: true,
					clearButton: false,
					autoClose: true,
					language: 'en',
				})

				$('[title]').tooltip()
			},
		})

		$('.datepicker-here').datepicker({
			dateFormat: 'yyyy-mm-dd',
			todayButton: true,
			clearButton: false,
			autoClose: true,
			language: 'en',
		})

		bsCustomFileInput.init()

		// channel.bind('kirim-pengaduan-event', function(data) {
		//     datatable.ajax.reload()
		// });
		// socket.on('backend-pengaduan-terima', function(data) {
		//     datatable.ajax.reload()
		// })
		// ================================================== //

		/**
		 * Keperluan CRUD
		 */
		// ================================================== //
		$get = (element) => {
			let row = datatable.row($(element).closest('tr')).data();
			$('#modal_detail').modal('show');
			$('#form_detail input#detail_name[name=name]').val(row.name);
			$('#form_detail input#detail_email[name=email]').val(row.email);
			$('#form_detail input#detail_phone[name=phone]').val(row.phone);
			$('#form_detail input#detail_message[name=message]').val(row.message);
		}

		$chat = async (element) => {
			location.replace(BASE_URL + 'chat/' + $(element).data('id'))
		}
	})
</script>