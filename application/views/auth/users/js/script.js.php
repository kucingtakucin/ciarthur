<script>
    let datatable_user, $create_user, $update_user, $delete_user,
        $update_role, $activate_user, $deactivate_user;
    const BASE_URL = "<?= base_url($uri_segment) ?>"

    // Document ready
    $(() => {
        /**
         * Keperluan DataTable, Datepicker, Summernote dan BsCustomFileInput
         */
        // ================================================== //
        datatable_user = $('#datatable').DataTable({
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
                    }).prop('outerHTML') + ' Tambah User', // Tambah Data
                    action: (e, dt, node, config) => {
                        location.replace(BASE_URL + 'create_user')
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
                url: BASE_URL + 'data_user',
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
                    targets: [0, 1, 2, 3, 4, 5], // Sesuaikan dengan jumlah kolom
                    className: 'text-center'
                },
                {
                    targets: [0, 5],
                    searchable: false,
                    orderable: false,
                },
                {
                    targets: [6],
                    visible: false,
                    searchable: false,
                }
            ],
            order: [
                [6, 'asc']
            ],
            columns: [{ // 0
                    title: '#',
                    name: '#',
                    data: 'DT_RowIndex',
                },
                { // 1
                    title: 'Username',
                    name: 'username',
                    data: 'username',
                },
                { // 2
                    title: 'Email',
                    name: 'email',
                    data: 'email',
                },
                { // 3
                    title: 'Role',
                    name: 'nama_role',
                    data: 'nama_role',
                    render: (nama_role) => {
                        return $('<span>', {
                            html: nama_role,
                            class: 'badge badge-primary',
                        }).prop('outerHTML')
                    }
                },
                { // 4
                    title: 'Status',
                    name: 'active',
                    data: (data) => {
                        return $('<a>', {
                            html: eval(data.active) ? 'Active' : 'Inactive',
                            class: eval(data.active) ? 'badge badge-success deactivate-user' : 'badge badge-danger activate-user',
                            href: eval(data.active) ? BASE_URL + `deactivate/${data.id}` : BASE_URL + `activate/${data.id}`,
                            'data-id': data.id
                        }).prop('outerHTML')
                    }
                },
                { // 5
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
                            title: 'Ubah User'
                        })

                        let btn_delete = $('<button>', {
                            type: 'button',
                            class: 'btn btn-danger btn_delete',
                            'data-id': id,
                            html: $('<i>', {
                                class: 'fa fa-trash'
                            }).prop('outerHTML'),
                            title: 'Hapus User'
                        })

                        return $('<div>', {
                            role: 'group',
                            class: 'btn-group btn-group-sm',
                            html: [btn_edit, btn_delete]
                        }).prop('outerHTML')
                    }
                },
                { // 6
                    title: 'Created On',
                    name: 'created_on',
                    data: 'created_on',
                }
            ],
            initComplete: function(event) {
                $(this).on('click', '.btn_edit', function(event) {
                    event.preventDefault()
                    location.replace(BASE_URL + `edit_user/${$(this).data('id')}`)
                });

                $(this).on('click', '.btn_delete', function(event) {
                    event.preventDefault()
                    $delete_user(this);
                });

                $(this).on('click', '.activate-user', function(event) {
                    event.preventDefault()
                    $activate_user(this)
                })

                $('[title]').tooltip()
                // ================================================== //
            },
        })

        datatable_user.on('draw.dt', function() {
            let PageInfo = datatable_user.page.info();
            datatable_user.column(0, {
                page: 'current'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            });
        });

        // socket.on('auth-reload_dt-user', () => {
        //     datatable_user.ajax.reload();
        // })
        // ================================================== //


        /**
         * Keperluan CRUD
         */
        // ================================================== //

        $create_user = async (event) => {
            event.preventDefault()
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Pastikan data yang terisi sudah benar!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, sudah!'
            }).then(async (result) => {
                if (result.isConfirmed && event.target.checkValidity()) {
                    $('#create_user input[type=submit]').hide();
                    $('#create_user button.loader').show();
                    loading()

                    let formData = new FormData(event.target);
                    formData.append(
                        await csrf().then(csrf => csrf.token_name),
                        await csrf().then(csrf => csrf.hash)
                    )

                    axios.post(BASE_URL + 'create_user', formData)
                        .then(res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: res.data.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                // socket.emit('auth-crud-user', {})
                                datatable_user.ajax.reload()
                                location.replace(BASE_URL)
                            })

                        }).catch(err => {
                            console.error(err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: err.response.data.message,
                                // text: err.response.statusText,
                            })
                        }).then(() => {
                            $('#create_user input[type=submit]').show();
                            $('#create_user button.loader').hide();
                            $('#create_user').trigger('reset');
                            $('#create_user').removeClass('was-validated')
                            $('#create_user select').val(null).trigger('change')
                        })
                }
            })
        }

        $update_user = async (event, id) => {
            event.preventDefault()
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Pastikan data yang terisi sudah benar!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, sudah!'
            }).then(async (result) => {
                if (result.isConfirmed && event.target.checkValidity()) {
                    $('#update_user input[type=submit]').hide();
                    $('#update_user button.loader').show();
                    loading()

                    let formData = new FormData(event.target);
                    formData.append(
                        await csrf().then(csrf => csrf.token_name),
                        await csrf().then(csrf => csrf.hash)
                    )

                    axios.post(BASE_URL + 'edit_user/' + id, formData)
                        .then(res => {

                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: res.data.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                socket.emit('auth-crud-user')
                                location.replace(BASE_URL)
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
                            $('#update_user input[type=submit]').show();
                            $('#update_user button.loader').hide();
                            $('#update_user').trigger('reset');
                            $('#update_user select').val(null).trigger('change')
                            $('#update_user').removeClass('was-validated')
                        })
                }
            })
        }

        $delete_user = async (element) => {
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

                    axios.post(BASE_URL + 'delete_user', formData)
                        .then(res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: res.data.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                // socket.emit('auth-crud-user', {})
                                datatable_user.ajax.reload()
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

        $activate_user = async (element) => {

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Untuk mengaktifkan user ini",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    $('#activate_user input[type=submit]').hide();
                    $('#activate_user button.loader').show();
                    loading()

                    let formData = new FormData();
                    formData.append(
                        await csrf().then(csrf => csrf.token_name),
                        await csrf().then(csrf => csrf.hash)
                    )

                    axios.post(BASE_URL + 'activate/' + $(element).data('id'), formData)
                        .then(res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: res.data.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                socket.emit('auth-activate-user', {})
                                // location.replace(BASE_URL)
                            })

                        }).catch(err => {
                            console.error(err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                // html: err.response.data.message,
                                text: err.response.statusText
                            })
                        }).then(() => {
                            $('#activate_user input[type=submit]').show();
                            $('#activate_user button.loader').hide();
                            $('#activate_user').trigger('reset');
                            $('#activate_user select').val(null).trigger('change')
                            $('#activate_user').removeClass('was-validated')
                        })
                }
            })
        }

        $deactivate_user = async (event, id) => {
            event.preventDefault()

            $('#deactivate_user input[type=submit]').hide();
            $('#deactivate_user button.loader').show();
            loading()

            let formData = new FormData(event.target);
            formData.append(
                await csrf().then(csrf => csrf.token_name),
                await csrf().then(csrf => csrf.hash)
            )

            axios.post(BASE_URL + 'deactivate/' + id, formData)
                .then(res => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: res.data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        socket.emit('auth-deactivate-user', {})
                        location.replace(BASE_URL)
                    })

                }).catch(err => {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        // html: err.response.data.message,
                        text: err.response.statusText
                    })
                }).then(() => {
                    $('#deactivate_user input[type=submit]').show();
                    $('#deactivate_user button.loader').hide();
                    $('#deactivate_user').trigger('reset');
                    $('#deactivate_user select').val(null).trigger('change')
                    $('#deactivate_user').removeClass('was-validated')
                })
        }
    })
</script>