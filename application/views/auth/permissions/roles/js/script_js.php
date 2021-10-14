<script>
    let datatable_permission, status_crud = false,
        $add_permission, $update_permission, $delete_permission;
    const BASE_URL = "<?= base_url($uri_segment) ?>"

    // Document ready
    $(() => {
        /**
         * Keperluan DataTable, Datepicker, Summernote dan BsCustomFileInput
         */
        // ================================================== //

        datatable_role_permission = $('#datatable-role-permissions').DataTable({
            processing: true,
            destroy: true,
            ordering: false,
            dom: `
                <"d-flex flex-row justify-content-end flex-wrap mb-2"B>
                <"d-flex flex-row justify-content-between"lf>
                rt
                <"d-flex flex-row justify-content-between"ip>`,
            buttons: {
                /** Tombol-tombol Export & Tambah Data */
                buttons: [{}, ],
                dom: {
                    button: {
                        className: 'btn'
                    },
                    buttonLiner: {
                        tag: null
                    }
                }
            },
            columnDefs: [{
                targets: [0, 1, 2, 3, 4], // Sesuaikan dengan jumlah kolom
                className: 'text-center'
            }, ],
        })

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
                buttons: [{}, ],
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
                url: BASE_URL + 'data_roles',
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
                    targets: [0, 2],
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
                    title: 'Role',
                    name: 'name',
                    data: 'name',
                    render: (name) => {
                        return $('<span>', {
                            html: name.charAt(0).toUpperCase() + name.slice(1),
                            class: 'badge badge-primary',
                        }).prop('outerHTML')
                    }
                },
                { // 2
                    title: 'Aksi',
                    name: 'id',
                    data: 'id',
                    render: (id) => {
                        let btn_manage = $('<button>', {
                            type: 'button',
                            class: 'btn btn-dark text-white btn_manage',
                            'data-id': id,
                            html: $('<i>', {
                                class: 'fa fa-cog'
                            }).prop('outerHTML'),
                            title: 'Manage Permission'
                        })

                        return $('<div>', {
                            role: 'group',
                            class: 'btn-group btn-group-sm',
                            html: [btn_manage]
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
                $(this).on('click', '.btn_manage', function(event) {
                    event.preventDefault()
                    location.replace(BASE_URL + `role_permissions/${$(this).data('id')}`)
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

        $add_permission = async (event) => {
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
                    $('#add_permission input[type=submit]').hide();
                    $('#add_permission button.loader').show();
                    status_crud = true
                    loading()

                    let formData = new FormData(event.target);
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
                            $('#add_permission input[type=submit]').show();
                            $('#add_permission button.loader').hide();
                            $('#add_permission').trigger('reset');
                            $('#add_permission select').val(null).trigger('change')
                            $('#add_permission').removeClass('was-validated')
                        })
                }
            })
        }

        $update_permission = async (event, id) => {
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
                    $('#update_permission input[type=submit]').hide();
                    $('#update_permission button.loader').show();
                    status_crud = true
                    loading()

                    let formData = new FormData(event.target);
                    formData.append(
                        await csrf().then(csrf => csrf.token_name),
                        await csrf().then(csrf => csrf.hash)
                    )

                    axios.post(BASE_URL + 'update_permission/' + id, formData)
                        .then(res => {

                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: res.data.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                // socket.emit('auth-crud-permission')
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
                            $('#update_permission input[type=submit]').show();
                            $('#update_permission button.loader').hide();
                            $('#update_permission').trigger('reset');
                            $('#update_permission select').val(null).trigger('change')
                            $('#update_permission').removeClass('was-validated')
                        })
                }
            })
        }

        $delete_permission = async (element) => {
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
                    status_crud = true
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
                            })

                            socket.emit('auth-crud-permission', {})
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

        $role_permissions = async (event, id) => {
            event.preventDefault()

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Pastikan permission yang dipilih sudah benar!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, sudah!'
            }).then(async (result) => {
                if (result.isConfirmed && event.target.checkValidity()) {
                    $('#role_permissions input[type=submit]').hide();
                    $('#role_permissions button.loader').show();
                    status_crud = true
                    loading()

                    let formData = new FormData(event.target);
                    formData.append(
                        await csrf().then(csrf => csrf.token_name),
                        await csrf().then(csrf => csrf.hash)
                    )

                    axios.post(BASE_URL + 'role_permissions/' + id, formData)
                        .then(res => {

                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: res.data.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                // socket.emit('auth-crud-permission')
                                location.reload()
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
                            $('#role_permissions input[type=submit]').show();
                            $('#role_permissions button.loader').hide();
                            $('#role_permissions').trigger('reset');
                            $('#role_permissions select').val(null).trigger('change')
                            $('#role_permissions').removeClass('was-validated')
                        })
                }
            })

        }
    })
</script>