<script>
    const BASE_URL = "<?= base_url($uri_segment) ?>"
    let datatable, status_crud = false,
        $insert, $update, $delete;
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
                        $insert();
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
                    if (!status_crud) {
                        loading()
                    }
                },
                complete: () => {
                    if (status_crud) {
                        status_crud = false
                    }
                    setTimeout(async () => {
                        await Swal.hideLoading()
                        await Swal.close()
                    }, 10);
                }
            },
            columnDefs: [{
                    targets: [0, 1, 2], // Sesuaikan dengan jumlah kolom
                    className: 'text-center'
                },
                {
                    targets: [0, 2, 3],
                    searchable: false,
                    orderable: false,
                },
                {
                    targets: [3],
                    visible: false,
                    searchable: false,
                }
            ],
            order: [
                [3, 'desc']
            ],
            columns: [{ // 0
                    title: '#',
                    name: '#',
                    data: 'DT_RowIndex',
                },
                { // 1
                    title: 'Fakultas',
                    name: 'nama',
                    data: 'nama',
                    render: (nama) => {
                        return $('<span>', {
                            html: nama,
                            class: 'nama'
                        }).prop('outerHTML')
                    }
                },
                { // 2
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
                { // 3
                    title: 'Created At',
                    name: 'created_at',
                    data: 'created_at',
                }
            ],
            initComplete: function(event) {
                $(this).on('click', '.btn_edit', function(event) {
                    event.preventDefault()
                    $update(this);
                });

                $(this).on('click', '.btn_delete', function(event) {
                    event.preventDefault()
                    $delete(this);
                });

                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    endDate: 'now',
                    clearBtn: true,
                    todayBtn: 'linked',
                    autoclose: true
                })

                $('[title]').tooltip()
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

        bsCustomFileInput.init()

        // socket.on('backend-reload_dt-mahasiswa', () => {
        //     initMap()
        //     datatable.ajax.reload();
        // })
        // ================================================== //

        /**
         * Keperluan CRUD
         */
        // ================================================== //

        $insert = () => {
            Swal.fire({
                title: 'Form Tambah Data',
                width: '800px',
                icon: 'info',
                html: `<?= $this->load->view("contents/$uri_segment/components/form_tambah", '', true); ?>`,
                confirmButtonText: '<i class="fa fa-check-square-o"></i> Simpan Data',
                showCancelButton: true,
                focusConfirm: false,
                showLoaderOnConfirm: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showCloseButton: true,
                reverseButtons: true,
                preConfirm: async () => {
                    let formData = new FormData(document.getElementById('form_tambah'));

                    formData.append(
                        await csrf().then(csrf => csrf.token_name),
                        await csrf().then(csrf => csrf.hash)
                    )

                    let response = await axios.post(BASE_URL + 'insert', formData)
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
                    }).then(() => {
                        datatable.ajax.reload()
                    })
                }
            })
        }

        $update = (element) => {
            let row = datatable.row($(element).closest('tr')).data();

            Swal.fire({
                title: 'Form Ubah Data',
                width: '800px',
                html: `<?= $this->load->view("contents/$uri_segment/components/form_ubah", '', true) ?>`,
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
                    $('#form_ubah #input_ubah_nama').val(row.nama)
                },
                preConfirm: async () => {
                    let formData = new FormData(document.getElementById('form_ubah'));

                    formData.append(
                        await csrf().then(csrf => csrf.token_name),
                        await csrf().then(csrf => csrf.hash)
                    )
                    formData.append('id', row.id)

                    let response = await axios.post(BASE_URL + 'update', formData)
                        .then(res => res.data.message)
                        .catch(err => {
                            let errors = err.response.data.errors;
                            if (typeof errors === 'object') {
                                Object.entries(errors).map(([key, value]) => {
                                    $(`#input_ubah_${key}`).addClass('is-invalid')
                                    $(`#error_ubah_${key}`).html(value).fadeIn(500)
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
                    }).then(() => {
                        datatable.ajax.reload()
                    })
                }
            })
        }

        $delete = async (element) => {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                showCancelButton: true,
                showLoaderOnConfirm: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showCloseButton: true,
                reverseButtons: true,
            }).then(async (result) => {
                if (result.isConfirmed) {
                    status_crud = true
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
    })
</script>