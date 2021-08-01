<script>
    const BASE_URL = "<?= base_url($uri_segment) ?>"
    let datatable, id_data, get_data,
        tambah_data, ubah_data, hapus_data;

    /**
     * Keperluan DataTable, Datepicker, dan BsCustomFileInput
     */
    // ================================================== //
    datatable = $('#table_data').DataTable({
        serverSide: true,
        processing: true,
        ordering: true,
        destroy: true,
        autoWidth: false,
        ajax: {
            url: BASE_URL + 'data',
            type: 'GET',
            dataType: 'JSON',
            data: {}
        },
        columnDefs: [{
                targets: [0, 1, 2, 3, 4, 5, 6, 7], // Sesuaikan dengan jumlah kolom
                className: 'text-center'
            },
            {
                searchable: false,
                orderable: false,
                targets: 0
            }
        ],
        order: [
            [1, 'desc']
        ],
        columns: [{
                title: '#',
                name: '#',
                data: null,
                defaultContent: ''
            },
            {
                title: 'Foto',
                name: 'foto_thumb',
                data: 'foto_thumb',
                render: (foto_thumb) => {
                    return $('<img>', {
                        src: `<?= BASE_URL() ?>uploads/mahasiswa/${foto_thumb}`,
                        class: "img-thumnail rounded-circle",
                        alt: 'Foto'
                    }).prop('outerHTML')
                }
            }, {
                title: 'NIM',
                name: 'nim',
                data: 'nim',
            }, {
                title: 'Nama',
                name: 'nama',
                data: 'nama',
            }, {
                title: 'Program Studi',
                name: 'nama_prodi',
                data: 'nama_prodi',
            }, {
                title: 'Fakultas',
                name: 'nama_fakultas',
                data: 'nama_fakultas',
            }, {
                title: 'Angkatan',
                name: 'angkatan',
                data: 'angkatan',
            }, {
                title: 'Aksi',
                name: 'id',
                data: 'id',
                render: (id) => {
                    let tombol_ubah = $('<button>', {
                        type: 'button',
                        class: 'btn btn-success tombol_ubah',
                        'data-id': id,
                        html: $('<i>', {
                            class: 'fa fa-edit'
                        }).prop('outerHTML'),
                        title: 'Ubah Data'
                    })

                    let tombol_hapus = $('<button>', {
                        type: 'button',
                        class: 'btn btn-danger tombol_hapus',
                        'data-id': id,
                        html: $('<i>', {
                            class: 'fa fa-trash'
                        }).prop('outerHTML'),
                        title: 'Hapus Data'
                    })

                    return $('<div>', {
                        role: 'group',
                        class: 'btn-group btn-group-sm',
                        html: [tombol_ubah, tombol_hapus]
                    }).prop('outerHTML')
                }
            }
        ],
    })

    datatable.on('draw.dt', function() {
        let PageInfo = datatable.page.info();
        datatable.column(0, {
            page: 'current'
        }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1 + PageInfo.start;
        });
    });

    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        startDate: 'now',
        clearBtn: true,
    }).change(function(event) {
        $(this).datepicker('hide')
    })

    bsCustomFileInput.init()
    // ================================================== //

    /**
     * Keperluan filter menggunakan select2
     */
    // ================================================== //
    $('#filter_fakultas').select2({
        placeholder: 'Pilih Fakultas',
        width: '100%',
        ajax: {
            url: BASE_URL + 'get_fakultas',
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
        $(`#filter_prodi`).prop('disabled', false)
        datatable.ajax.url(BASE_URL + 'data?' + (
            new URLSearchParams({
                fakultas_id: event.params.data.id
            }).toString()
        )).draw();

        $(`#filter_prodi`).select2({
            placeholder: 'Pilih Program Studi',
            width: '100%',
            ajax: {
                url: BASE_URL + 'get_prodi',
                dataType: 'JSON',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term, // search term
                        fakultas_id: event.params.data.id
                    };
                },
                processResults: function(response) {
                    let myResults = [];
                    response.data.map(item => {
                        myResults.push({
                            'id': item.id,
                            'fakultas_id': event.params.data.id,
                            'text': item.nama
                        });
                    })
                    return {
                        results: myResults
                    };
                }
            }
        }).on('select2:select', function(event) {
            datatable.ajax.url(BASE_URL + 'data?' + (
                new URLSearchParams({
                    fakultas_id: event.params.data.fakultas_id,
                    prodi_id: event.params.data.id,
                }).toString()
            )).draw();
        })
    })

    // ================================================== //

    /**
     * Keperluan CRUD
     */
    // ================================================== //
    get_data = (form) => {
        let formData = new FormData();
        formData.append('id', $(form).data('id'));

        fetch(BASE_URL + 'get_where', {
            method: 'POST',
            body: formData,
        }).then(response => {
            if (response.ok) return response.json()
            throw new Error(response.statusText)
        }).then(response => {
            let row = response.data;
            id_data = row.id;
            $('#modal_ubah').modal('show');
            $('#form_ubah input[name=nim]').val(row.nim);
            $('#form_ubah input[name=nama]').val(row.nama);
            $('#form_ubah input[name=angkatan]').val(row.angkatan);

            $('#form_ubah .select_fakultas')
                .append(new Option(row.nama_fakultas, row.fakultas_id, true, true))
                .trigger('change')
                .trigger({
                    type: 'select2:select',
                    params: {
                        data: {
                            id: row.fakultas_id,
                            fakultas_id: row.fakultas_id,
                            prodi_id: row.prodi_id
                        }
                    }
                })

            $('#form_ubah .select_prodi')
                .append(new Option(row.nama_prodi, row.prodi_id, true, true))
                .trigger('change')
                .trigger({
                    type: 'select2:select',
                    params: {
                        data: {
                            fakultas_id: row.fakultas_id,
                            prodi_id: row.prodi_id
                        }
                    }
                })

            $('#form_ubah input[name=old_foto]').val(row.foto)
            $('#form_ubah input[name=old_foto_thumb]').val(row.foto_thumb)
            if (row.foto) {
                $('#lihat').removeClass('text-danger')
                $('#lihat').addClass('text-success')
                $('#lihat').html(`<a href="<?= BASE_URL() ?>uploads/mahasiswa/${row.foto}" target="_blank">Lihat file</a>`)
            } else {
                $('#lihat').addClass('text-danger')
                $('#lihat').removeClass('text-success')
                $('#lihat').html('File belum ada')
            }
        }).catch(error => {
            console.error(error)
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
                showConfirmButton: false,
                timer: 1500
            })
        })
    }

    tambah_data = (form) => {
        let formData = new FormData(form);

        fetch(BASE_URL + 'insert', {
            method: 'POST',
            body: formData
        }).then(response => {
            $('#form_tambah button[type=submit]').hide();
            $('#form_tambah button.loader').show();
            if (response.ok) return response.json()
            throw new Error(response.statusText)
        }).then(response => {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.message,
                showConfirmButton: false,
                timer: 1500
            })
            datatable.ajax.reload();
        }).catch(error => {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
                showConfirmButton: false,
                timer: 1500
            })
        }).finally(() => {
            $('#form_tambah button[type=submit]').show();
            $('#form_tambah button.loader').hide();
            $('#form_tambah').trigger('reset');
            $('#form_tambah select').val(null).trigger('change')
            $('#form_tambah').removeClass('was-validated')
            $('#modal_tambah').modal('hide');
        })
    }

    ubah_data = (form) => {
        let formData = new FormData(form);
        formData.append('id', id_data);

        fetch(BASE_URL + 'update', {
            method: 'POST',
            body: formData
        }).then(response => {
            $('#form_ubah button[type=submit]').hide();
            $('#form_ubah button.loader').show();
            if (response.ok) return response.json()
            throw new Error(response.statusText)
        }).then(response => {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.message,
                showConfirmButton: false,
                timer: 1500
            })
            datatable.ajax.reload();
        }).catch(error => {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
                showConfirmButton: false,
                timer: 1500
            })
        }).finally(() => {
            $('#form_ubah button[type=submit]').show();
            $('#form_ubah button.loader').hide();
            $('#form_ubah').trigger('reset');
            $('#form_ubah select').val(null).trigger('change')
            $('#form_ubah').removeClass('was-validated')
            $('#modal_ubah').modal('hide');
        })
    }

    hapus_data = (form) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                let formData = new FormData();
                formData.append('id', $(form).data('id'));

                fetch(BASE_URL + 'delete', {
                    method: 'POST',
                    body: formData
                }).then(response => {
                    if (response.ok) return response.json()
                    throw new Error(response.statusText)
                }).then(response => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                    datatable.ajax.reload();
                }).catch(error => {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        showConfirmButton: false,
                        timer: 1500
                    })
                })
            }
        })
    }
    // ================================================== //

    /**
     * Keperluan event click tombol dan submit form
     */
    // ================================================== //
    $('#tombol_tambah').click(event => {
        event.preventDefault();
        $('#modal_tambah').modal('show');
    });

    $('#table_data').on('click', '.tombol_ubah', function(event) {
        event.preventDefault()
        get_data(this);
    });

    $('#table_data').on('click', '.tombol_hapus', function(event) {
        event.preventDefault()
        hapus_data(this);
    });

    $('#form_tambah').submit(function(event) {
        event.preventDefault()
        if (this.checkValidity()) {
            tambah_data(this);
        }
    });

    $('#form_ubah').submit(function(event) {
        event.preventDefault();
        if (this.checkValidity()) {
            ubah_data(this);
        }
    });
    // ================================================== //

    /**
     * Keperluan input select2 didalam form
     */
    // ================================================== //
    const select2_in_form = (status) => {
        $(`#form_${status} .select_fakultas`).select2({
            placeholder: 'Pilih Fakultas',
            width: '100%',
            dropdownParent: $(`#modal_${status}`),
            ajax: {
                url: BASE_URL + 'get_fakultas',
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
            $(`#form_${status} .select_prodi`).prop('disabled', false)
            $(`#form_${status} .select_prodi`).select2({
                placeholder: 'Pilih Program Studi',
                width: '100%',
                dropdownParent: $(`#modal_${status}`),
                ajax: {
                    url: BASE_URL + 'get_prodi',
                    dataType: 'JSON',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term, // search term
                            fakultas_id: event.params.data.id
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

    $('#modal_tambah').on('show.bs.modal', () => {
        select2_in_form('tambah')
    })

    $('#modal_ubah').on('show.bs.modal', () => {
        select2_in_form('ubah')
    })
    // ================================================== //
</script>