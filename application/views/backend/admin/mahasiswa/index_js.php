<script>
    const BASE_URL = "<?= base_url($uri_segment) ?>"
    let datatable, id_data, get_data, csrf,
        tambah_data, ubah_data, hapus_data;
    // Document ready
    $(() => {

        /**
         * Keperluan generate csrf
         */
        // ================================================== //
        csrf = async () => {
            let formData = new FormData()
            formData.append('key', '<?= $this->encryption->encrypt(bin2hex('csrf')) ?>')

            let response = await fetch('<?= base_url('csrf/generate') ?>', {
                method: 'POST',
                body: formData
            }).then(response => {
                if (response.ok) return response.json()
                throw new Error(response.statusText)
            })

            return {
                token_name: response.csrf_token_name,
                hash: response.csrf_hash
            }
        }

        /**
         * Keperluan disable inspect element
         */
        // ================================================== //
        // Disable right click
        $(document).contextmenu(function(event) {
            event.preventDefault()
        })

        $(document).keydown(function(event) {
            // Disable F12
            if (event.keyCode == 123) return false;

            // Disable Ctrl + Shift + I
            if (event.ctrlKey && event.shiftKey && event.keyCode == 'I'.charCodeAt(0)) {
                return false;
            }

            // Disable Ctrl + Shift + J
            if (event.ctrlKey && event.shiftKey && event.keyCode == 'J'.charCodeAt(0)) {
                return false;
            }

            // Disable Ctrl + U
            if (event.ctrlKey && event.keyCode == 'U'.charCodeAt(0)) {
                return false;
            }
        })

        /**
         * Keperluan DataTable, Datepicker, Summernote dan BsCustomFileInput
         */
        // ================================================== //
        datatable = $('#table_data').DataTable({
            serverSide: true,
            processing: true,
            destroy: true,
            responsive: true,
            ajax: {
                url: BASE_URL + 'data',
                type: 'GET',
                dataType: 'JSON',
                data: {},
                beforeSend: () => {
                    Swal.fire({
                        title: 'Loading...',
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    })
                },
                complete: () => {
                    Swal.close()
                }
            },
            columnDefs: [{
                    targets: [0, 1, 2, 3, 4, 5, 6, 7], // Sesuaikan dengan jumlah kolom
                    className: 'text-center'
                },
                {
                    searchable: false,
                    orderable: false,
                    targets: [0, 7]
                }
            ],
            order: [
                [7, 'desc']
            ],
            columns: [{
                    title: '#',
                    name: '#',
                    data: 'DT_Row_Index',
                },
                {
                    title: 'Foto',
                    name: 'foto_thumb',
                    data: 'foto_thumb',
                    render: (foto_thumb) => {
                        return $('<img>', {
                            src: `<?= base_url() ?>uploads/mahasiswa/${foto_thumb}`,
                            class: "img-thumnail",
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
                    render: (nama) => {
                        return $('<span>', {
                            html: nama,
                            class: 'nama'
                        }).prop('outerHTML')
                    }
                },
                {
                    title: 'Program Studi',
                    name: 'nama_prodi',
                    data: 'nama_prodi',
                },
                {
                    title: 'Fakultas',
                    name: 'nama_fakultas',
                    data: 'nama_fakultas',
                },
                {
                    title: 'Angkatan',
                    name: 'angkatan',
                    data: 'angkatan',
                },
                {
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
            format: 'yyyy-mm-dd',
            endDate: 'now',
            clearBtn: true,
            todayBtn: 'linked',
            autoclose: true
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
            datatable.column('nama_fakultas:name')
                .search(event.params.data.text)
                .draw()

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
                datatable.column('nama_prodi:name')
                    .search(event.params.data.text)
                    .draw()
            })
        })

        // ================================================== //

        /**
         * Keperluan CRUD
         */
        // ================================================== //
        get_data = async (form) => {
            Swal.fire({
                title: 'Loading...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            })

            let formData = new FormData();
            formData.append('id', $(form).data('id'));
            formData.append(
                await csrf().then(csrf => csrf.token_name),
                await csrf().then(csrf => csrf.hash)
            )

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
                $('#form_ubah input#ubah_nim[name=nim]').val(row.nim);
                $('#form_ubah input#ubah_nama[name=nama]').val(row.nama);
                $('#form_ubah input#ubah_angkatan[name=angkatan]').val(row.angkatan);

                $('#form_ubah select#ubah_select_fakultas.select_fakultas')
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

                $('#form_ubah select#ubah_select_prodi.select_prodi')
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
            }).catch(error => {
                console.error(error)
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                    showConfirmButton: false,
                    timer: 1500
                })
            }).finally(() => {
                Swal.close()
            })
        }

        tambah_data = async (form) => {
            Swal.fire({
                title: 'Loading...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            })

            let formData = new FormData(form);
            formData.append(
                await csrf().then(csrf => csrf.token_name),
                await csrf().then(csrf => csrf.hash)
            )

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

        ubah_data = async (form) => {
            Swal.fire({
                title: 'Loading...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            })

            let formData = new FormData(form);
            formData.append('id', id_data);
            formData.append(
                await csrf().then(csrf => csrf.token_name),
                await csrf().then(csrf => csrf.hash)
            )

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

        hapus_data = async (form) => {
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
                    Swal.fire({
                        title: 'Loading...',
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    })

                    let formData = new FormData();
                    formData.append('id', $(form).data('id'));
                    formData.append(
                        await csrf().then(csrf => csrf.token_name),
                        await csrf().then(csrf => csrf.hash)
                    )

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
         * Keperluan event click tombol, reset, export, validasi dan submit form
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

        $('#modal_tambah').on('hide.bs.modal', () => {
            $('#form_tambah').removeClass('was-validated')
            $('#form_tambah').trigger('reset')
        })

        $('#modal_ubah').on('hide.bs.modal', () => {
            $('#form_ubah').removeClass('was-validated')
            $('#form_ubah').trigger('reset')
        })

        $('#tombol_export_excel').click(function() {
            location.replace(BASE_URL + 'export_excel');
        })

        $('#tombol_export_word').click(function() {
            location.replace(BASE_URL + 'export_word');
        })

        $('#tombol_export_pdf').click(function() {
            location.replace(BASE_URL + 'export_pdf');
        })

        // ================================================== //

        /**
         * Keperluan input select2 didalam form
         */
        // ================================================== //
        const select2_in_form = (status) => {
            $(`#form_${status} select#${status}_select_fakultas.select_fakultas`).select2({
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
                $(`#form_${status} select#${status}_select_prodi.select_prodi`).prop('disabled', false)
                $(`#form_${status} select#${status}_select_prodi.select_prodi`).select2({
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

        /**
         * Keperluan WebGIS dengan Leaflet
         */
        // ================================================== //
        let map = L.map("map", {
            center: [-7.5828, 111.0444],
            zoom: 12,
            layers: [
                /** OpenStreetMap Tile Layer */
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }),
                // L.marker([-7.541355, 111.0377783], { //-7.641355, 111.0377783
                //     icon: L.icon({
                //         iconUrl: 'https://upload.wikimedia.org/wikipedia/commons/f/f2/678111-map-marker-512.png',
                //         iconSize: [40, 40], // size of the icon
                //         iconAnchor: [20, 40], // point of the icon which will correspond to marker's location
                //         popupAnchor: [0, -30] // point from which the popup should open relative to the iconAnchor
                //     })
                // }).bindPopup("<b>Hello world!</b><br>I am a popup.").openPopup()
            ]
        })

        let map_tambah = L.map("map-tambah", {
            center: [-7.5828, 111.0444],
            zoom: 12,
            layers: [
                /** OpenStreetMap Tile Layer */
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }),

            ]
        })

        let marker_tambah;
        map_tambah.on('click', (event) => {
            if (marker_tambah) map_tambah.removeLayer(marker_tambah)
            marker_tambah = L.marker([event.latlng.lat, event.latlng.lng], { //-7.641355, 111.0377783
                icon: L.icon({
                    iconUrl: 'https://upload.wikimedia.org/wikipedia/commons/f/f2/678111-map-marker-512.png',
                    iconSize: [40, 40], // size of the icon
                    iconAnchor: [20, 40], // point of the icon which will correspond to marker's location
                    popupAnchor: [0, -30] // point from which the popup should open relative to the iconAnchor
                })
            })
            marker_tambah.addTo(map_tambah)
            marker_tambah.bindPopup(`${event.latlng.lat}, ${event.latlng.lng}`).openPopup()

            $('#tambah_latitude').val(event.latlng.lat)
            $('#tambah_longitude').val(event.latlng.lng)
        })

        /** Legend */
        let legend = L.control({
            position: "bottomleft"
        })

        legend.onAdd = (map) => {
            let div = L.DomUtil.create("div", "legend");
            div.innerHTML += "<h3><b>KABUPATEN KARANGANYAR</b></h3>";
            return div;
        }

        legend.addTo(map)

        /** GeoJSON Features */
        $.getJSON(BASE_URL + 'get_geojson', response => {
            let geojson = L.geoJSON(response, {
                onEachFeature: (feature, layer) => {
                    layer.on({
                        mouseover: (event) => {
                            let layer = event.target;
                            layer.setStyle({
                                weight: 5,
                                dashArray: '',
                                fillOpacity: 0.7
                            });
                            if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
                                layer.bringToFront();
                            }
                        },
                        mouseout: (event) => {
                            geojson.resetStyle(event.target)
                        },
                        click: (event) => {
                            map.fitBounds(event.target.getBounds());
                        }
                    })
                }
            }).addTo(map)
        })

        fetch(BASE_URL + 'get_kecamatan')
            .then(response => {
                if (response.ok) return response.json()
                throw new Error(response.statusText)
            })
            .then(response => {
                response.data.map(item => {
                    L.marker([item.latitude, item.longitude])
                        .addTo(map)
                        .bindPopup(
                            new L.Popup({
                                autoClose: false,
                                closeOnClick: false
                            })
                            .setContent(`<b>${item.nama}</b>`)
                            .setLatLng([item.latitude, item.longitude])
                        ).openPopup();
                })
            })
    })
</script>