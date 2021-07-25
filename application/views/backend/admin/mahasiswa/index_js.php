<script>
    const URL = "<?= base_url($uri_segment) ?>"
    let datatable, id_data, get_data,
        tambah_data, ubah_data, hapus_data;

    $(() => {
        datatable = (() => {
            $('#table_data').DataTable({
                serverSide: true,
                processing: true,
                ordering: true,
                autoWidth: false,
                destroy: true,
                ajax: {
                    url: URL + 'data',
                    type: 'GET',
                    dataType: 'JSON',
                    data: {}
                },
                columnDefs: [{
                        targets: [0, 1, 2, 3, 4, 5], // Sesuaikan dengan jumlah kolom
                        className: 'text-center'
                    },
                    {
                        targets: [0, 5],
                        orderable: false,
                    }
                ],
                scrollX: true
            });
        })()

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            startDate: 'now'
        });

        bsCustomFileInput.init()
    });

    $(() => {
        $('#filter_fakultas').select2({
            placeholder: 'Pilih Fakultas',
            width: '100%',
            ajax: {
                url: URL + 'get_fakultas',
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
            $('#table_data').DataTable().ajax.url(URL + 'data?' + (
                new URLSearchParams({
                    fakultas_id: event.params.data.id
                }).toString()
            )).draw();

            $(`#filter_prodi`).select2({
                placeholder: 'Pilih Program Studi',
                width: '100%',
                ajax: {
                    url: URL + 'get_prodi',
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
                $('#table_data').DataTable().ajax.url(URL + 'data?' + (
                    new URLSearchParams({
                        fakultas_id: event.params.data.fakultas_id,
                        prodi_id: event.params.data.id,
                    }).toString()
                )).draw();
            })
        })

    });

    $(() => {
        get_data = (form) => {
            let formData = new FormData();
            formData.append('id', $(form).data('id'));

            $.ajax({
                url: URL + 'get_where',
                type: "POST",
                data: formData,
                dataType: 'JSON',
                processData: false,
                contentType: false,
                beforeSend: function() {},
                complete: function() {},
                error: function(error) {
                    console.log(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        showConfirmButton: false,
                        timer: 1500
                    })
                },
                success: function(data) {
                    if (data.status) {
                        let row = data.data;
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
                            $('#lihat').html(`<a href="<?= base_url() ?>uploads/mahasiswa/${row.foto}" target="_blank">Lihat file</a>`)
                        } else {
                            $('#lihat').addClass('text-danger')
                            $('#lihat').removeClass('text-success')
                            $('#lihat').html('File belum ada')
                        }
                    }
                },
            });
        }

        tambah_data = (form) => {
            let formData = new FormData(form);
            $.ajax({
                url: URL + 'insert',
                type: "POST",
                data: formData,
                dataType: 'JSON',
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#form_tambah button[type=submit]').hide();
                    $('#form_tambah button.loader').show();
                },
                complete: function() {
                    $('#form_tambah button[type=submit]').show();
                    $('#form_tambah button.loader').hide();
                    $('#form_tambah').trigger('reset');
                    $('#form_tambah select').val(null).trigger('change')
                    $('#form_tambah').removeClass('was-validated')
                    $('#modal_tambah').modal('hide');
                },
                error: function(error) {
                    console.log(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        showConfirmButton: false,
                        timer: 1500
                    })
                },
                success: function(data) {
                    if (data.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                        $('#table_data').DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                },
            });
        }

        ubah_data = (form) => {
            let formData = new FormData(form);
            formData.append('id', id_data);

            $.ajax({
                url: URL + 'update',
                type: "POST",
                data: formData,
                dataType: 'JSON',
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#form_ubah button[type=submit]').hide();
                    $('#form_ubah button.loader').show();
                },
                complete: function() {
                    $('#form_ubah button[type=submit]').show();
                    $('#form_ubah button.loader').hide();
                    $('#form_ubah').trigger('reset');
                    $('#form_ubah select').val(null).trigger('change')
                    $('#form_ubah').removeClass('was-validated')
                    $('#modal_ubah').modal('hide');
                },
                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        showConfirmButton: false,
                        timer: 1500
                    })
                },
                success: function(data) {
                    if (data.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                        $('#table_data').DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                },
            });
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
                    var formData = new FormData();
                    formData.append('id', $(form).data('id'));
                    $.ajax({
                        url: URL + 'delete',
                        type: "POST",
                        data: formData,
                        dataType: 'JSON',
                        processData: false,
                        contentType: false,
                        beforeSend: function() {},
                        complete: function() {},
                        error: function(error) {
                            console.log(error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        },
                        success: function(data) {
                            if (data.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: data.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                $('#table_data').DataTable().ajax.reload();
                            }
                        },
                    });
                }
            })
        }
    });

    $(() => {
        $('#tombol_tambah').click(event => {
            event.preventDefault();
            $('#modal_tambah').modal('show');
        });

        $('#table_body').on('click', '.tombol_ubah', function(event) {
            event.preventDefault()
            get_data(this);
        });

        $('#table_body').on('click', '.tombol_hapus', function(event) {
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
    });

    $(() => {
        const select2_in_form = (status) => {
            $(`#form_${status} .select_fakultas`).select2({
                    placeholder: 'Pilih Fakultas',
                    width: '100%',
                    dropdownParent: $(`#modal_${status}`),
                    ajax: {
                        url: URL + 'get_fakultas',
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
                .on('select2:select', function(event) {
                    $(`#form_${status} .select_prodi`).prop('disabled', false)
                    $(`#form_${status} .select_prodi`).select2({
                        placeholder: 'Pilih Program Studi',
                        width: '100%',
                        dropdownParent: $(`#modal_${status}`),
                        ajax: {
                            url: URL + 'get_prodi',
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
    });
</script>