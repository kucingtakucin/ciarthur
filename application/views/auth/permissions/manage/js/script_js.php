<script>
    let datatable_permission, $add_permission, $update_permission, $delete;
    const BASE_URL = "<?= base_url($uri_segment) ?>"

    // Document ready
    $(() => {
        /**
         * Keperluan DataTable, Datepicker, Summernote dan BsCustomFileInput
         */
        // ================================================== //
        datatable_permission = $('#datatable').DataTable({
            serverSide: true,
            processing: true,
            destroy: true,
            // responsive: true,
            dom: `
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
                    }).prop('outerHTML') + ' Tambah Permission', // Tambah Data
                    action: (e, dt, node, config) => {
                        // location.replace(BASE_URL + 'add_permission')
                        $('#modal_tambah').modal('show');
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
                url: BASE_URL + 'data_permission',
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
                    targets: [0, 1, 2, 3], // Sesuaikan dengan jumlah kolom
                    className: 'text-center'
                },
                {
                    targets: [0, 3],
                    searchable: false,
                    orderable: false,
                },
                {
                    targets: [4],
                    visible: false,
                    searchable: false,
                }
            ],
            order: [
                [4, 'desc']
            ],
            columns: [{ // 0
                    title: '#',
                    name: '#',
                    data: 'DT_RowIndex',
                },
                { // 1
                    title: 'Permission Key',
                    name: 'perm_key',
                    data: 'perm_key',
                },
                { // 2
                    title: 'Permission Name',
                    name: 'perm_name',
                    data: 'perm_name',
                },
                { // 3
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
                            title: 'Ubah Permission'
                        })

                        let btn_delete = $('<button>', {
                            type: 'button',
                            class: 'btn btn-danger btn_delete',
                            'data-id': id,
                            html: $('<i>', {
                                class: 'fa fa-trash'
                            }).prop('outerHTML'),
                            title: 'Hapus Permission'
                        })

                        return $('<div>', {
                            role: 'group',
                            class: 'btn-group btn-group-sm',
                            html: [btn_edit, btn_delete]
                        }).prop('outerHTML')
                    }
                },
                { // 4
                    title: 'Created At',
                    name: 'created_at',
                    data: 'created_at',
                }
            ],
            initComplete: function(event) {
                $(this).on('click', '.btn_edit', function(event) {
                    event.preventDefault()
                    // location.replace(BASE_URL + `update_permission/${$(this).data('id')}`)
                    $get(this)
                });

                $(this).on('click', '.btn_delete', function(event) {
                    event.preventDefault()
                    $delete(this);
                });

                $('[title]').tooltip()
                // ================================================== //
            },
        })

        datatable_permission.on('draw.dt', function() {
            let PageInfo = datatable_permission.page.info();
            datatable_permission.column(0, {
                page: 'current'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            });
        });

        // socket.on('auth-reload_dt-permission', () => {
        //     datatable_permission.ajax.reload();
        // })
        // ================================================== //


        /**
         * Keperluan CRUD
         */
        // ================================================== //

        $get = (element) => {
            let row = datatable_permission.row($(element).closest('tr')).data();
            $('#modal_ubah').modal('show');
            $('#form_ubah input#id[name=id]').val(row.id)
            $('#form_ubah input#ubah_perm_name[name=perm_name]').val(row.perm_name);
            $('#form_ubah input#ubah_perm_key[name=perm_key]').val(row.perm_key);
        }

        $insert = async (form) => {
            loading()

            let formData = new FormData(form);
            formData.append(
                await csrf().then(csrf => csrf.token_name),
                await csrf().then(csrf => csrf.hash)
            )

            axios.post(BASE_URL + 'add_permission', formData)
                .then(res => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: res.data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // socket.emit('auth-crud-permission')
                        datatable_permission.ajax.reload()
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
                    $('#form_tambah button[type=submit]').show();
                    $('#form_tambah button.loader').hide();
                    $('#form_tambah').trigger('reset');
                    $('#form_tambah select').val(null).trigger('change')
                    $('#form_tambah').removeClass('was-validated')
                    $('#modal_tambah').modal('hide');
                })
        }

        $update = async (form) => {
            loading()

            let formData = new FormData(form);
            formData.append(
                await csrf().then(csrf => csrf.token_name),
                await csrf().then(csrf => csrf.hash)
            )

            axios.post(BASE_URL + 'update_permission', formData)
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
                        // socket.emit('backend-crud-mahasiswa', {})
                        datatable_permission.ajax.reload()
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

                    axios.post(BASE_URL + 'delete_permission', formData)
                        .then(res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: res.data.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                // socket.emit('auth-crud-permission', {})
                                datatable_permission.ajax.reload()

                            })

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

        /**
         * Keperluan event click tombol, reset, export, validasi dan submit form
         */
        // ================================================== //
        $('#modal_tambah').on('hidden.bs.modal', function(event) {
            $('#form_tambah').trigger('reset')
            $('#form_tambah').removeClass('was-validated')
        })

        $('#modal_ubah').on('hidden.bs.modal', function(event) {
            $('#form_ubah').trigger('reset')
            $('#form_ubah').removeClass('was-validated')
        })

        $('#form_tambah').submit(function(event) {
            event.preventDefault()
            if (this.checkValidity()) {
                $insert(this);
            }
        });

        $('#form_ubah').submit(function(event) {
            event.preventDefault();
            if (this.checkValidity()) {
                $update(this);
            }
        });
    })
</script>