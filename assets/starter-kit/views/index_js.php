<script>
    $(document).ready(function() {

        load_table();

        $(".js-select2").select2({
            width: '100%'
        });

    });
</script>

<script>
    const URL = "<?= base_url($uri_segment) ?>";

    function load_table() {
        $('#table_data').dataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ordering: true,
            autoWidth: false,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            ajax: {
                url: URL + 'get_data',
                type: 'GET',
                dataType: 'JSON',
                data: function(d) {

                }
            },
            drawCallback: function(res) {

            },
            order: [],
            columnDefs: [{
                    targets: [0, 1, 2, 3],
                    className: 'text-center'
                },
                {
                    targets: [0, 3],
                    orderable: false,
                }
            ],
            scrollX: true
        });

    }
</script>

<script>
    $('#tombol_tambah').on('click', function(e) {
        e.preventDefault();
        $('#modal_tambah').modal('show');
    });

    $('#table_body').on('click', '.tombol_ubah', function(e) {
        e.preventDefault();
        get_data(this);
    });

    $('#table_body').on('click', '.tombol_hapus', function(e) {
        e.preventDefault();
        hapus_data(this);
    });
</script>

<script>
    var validation_tambah = function() {
        var validation_tambah = function() {
            jQuery('.js-validation-signin').validate({
                errorClass: 'invalid-feedback animated fadeInDown',
                errorElement: 'div',
                errorPlacement: function(error, e) {
                    jQuery(e).parents('.form-group > div').append(error);
                },
                highlight: function(e) {
                    jQuery(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
                },
                success: function(e) {
                    jQuery(e).closest('.form-group').removeClass('is-invalid');
                    jQuery(e).remove();
                },
                rules: {
                    'role': {
                        required: true,
                    },
                    'username': {
                        required: true,
                    },
                    'password': {
                        required: true,
                    }
                },
                messages: {
                    'role': {
                        required: 'Username tidak boleh kosong',
                    },
                    'username': {
                        required: 'Username tidak boleh kosong',
                    },
                    'password': {
                        required: 'Password tidak boleh kosong',
                    }
                }
            });

            $('#form_data').on('submit', function(e) {
                if ($(this).valid()) {
                    e.preventDefault();
                    submit_data(this);
                }
            });

        };

        return {
            init: function() {
                // Init Sign In Form Validation
                validation_tambah();
            }
        };
    }();

    var validation_ubah = function() {
        var validation_ubah = function() {
            jQuery('.js-validation-signin-edit').validate({
                errorClass: 'invalid-feedback animated fadeInDown',
                errorElement: 'div',
                errorPlacement: function(error, e) {
                    jQuery(e).parents('.form-group > div').append(error);
                },
                highlight: function(e) {
                    jQuery(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
                },
                success: function(e) {
                    jQuery(e).closest('.form-group').removeClass('is-invalid');
                    jQuery(e).remove();
                },
                rules: {
                    'role': {
                        required: true,
                    },
                    'username': {
                        required: true,
                    },
                },
                messages: {
                    'role': {
                        required: 'Username tidak boleh kosong',
                    },
                    'username': {
                        required: 'Username tidak boleh kosong',
                    },
                }
            });

            $('#form_ubah').on('submit', function(e) {
                if ($(this).valid()) {
                    e.preventDefault();
                    ubah_data(this);
                }
            });

        };

        return {
            init: function() {
                // Init Sign In Form Validation
                validation_ubah();
            }
        };
    }();

    $(document).ready(function() {

        jQuery(function() {
            validation_tambah.init();
            validation_ubah.init();
        });

    });
</script>

<script>
    var id_data;

    function submit_data(form) {
        var data = new FormData(form);

        $.ajax({
            url: URL + 'store',
            type: "POST",
            data: data,
            dataType: 'JSON',
            processData: false,
            contentType: false,
            beforeSend: function() {
                // console.log('sedang menghapus');
                $('#form_data button[type=submit]').attr('disabled', true);
            },
            complete: function() {
                // console.log('Berhasil');
                $('#form_data button[type=submit]').attr('disabled', false);
            },
            error: function(e) {
                console.log(e);
                toastr.error('gagal, terjadi kesalahan');
                $('#form_data button[type=submit]').attr('disabled', false);
            },
            success: function(data) {
                if (data.status == 'success') {
                    toastr.success(data.msg);
                    $('#modal_tambah').modal('hide');
                    $('#form_data').trigger('reset');
                    $('#table_data').DataTable().ajax.reload();
                } else {
                    toastr.error(data.msg);
                }
            },
        });
    }

    function ubah_data(form) {
        var data = new FormData(form);
        data.append('id', id_data);

        $.ajax({
            url: URL + 'update',
            type: "POST",
            data: data,
            dataType: 'JSON',
            processData: false,
            contentType: false,
            beforeSend: function() {
                // console.log('sedang menghapus');                
                $('#form_ubah button[type=submit]').attr('disabled', true);
            },
            complete: function() {
                // console.log('Berhasil');
                $('#form_ubah button[type=submit]').attr('disabled', false);
            },
            error: function(e) {
                console.log(e);
                toastr.error('gagal, terjadi kesalahan');
                $('#form_ubah button[type=submit]').attr('disabled', false);
            },
            success: function(data) {
                if (data.status == 'success') {
                    toastr.success(data.msg);
                    $('#form_ubah').trigger('reset');
                    $('#modal_ubah').modal('hide');
                    $('#table_data').DataTable().ajax.reload();
                } else {
                    toastr.error(data.msg);
                }
            },
        });
    }

    function hapus_data(form) {
        var id = $(form).attr('data-id');

        Swal.fire({
            title: 'Hapus Data ?',
            text: "Data akan dihapus dari sistem",
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {

                var data = new FormData();
                data.append('id', id);

                $.ajax({
                    url: URL + 'delete',
                    type: "POST",
                    data: data,
                    dataType: 'JSON',
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        // console.log('sedang menghapus');
                    },
                    complete: function() {
                        // console.log('Berhasil');
                    },
                    error: function(e) {
                        console.log(e);
                        toastr.error('gagal, terjadi kesalahan');
                    },
                    success: function(data) {
                        if (data.status == 'success') {
                            toastr.success(data.msg);
                            $('#table_data').DataTable().ajax.reload();
                        }
                    },
                });
            } else {
                return false;
            }
        })
    }

    function get_data(form) {
        var id = $(form).attr('data-id');
        var data = new FormData();
        data.append('id', id);

        $.ajax({
            url: URL + 'get',
            type: "POST",
            data: data,
            dataType: 'JSON',
            processData: false,
            contentType: false,
            beforeSend: function() {
                // console.log('sedang menghapus');
            },
            complete: function() {
                // console.log('Berhasil');
            },
            error: function(e) {
                console.log(e);
                toastr.error('gagal, terjadi kesalahan');
            },
            success: function(data) {
                if (data.status == 'success') {
                    var row = data.data;
                    id_data = row.id;
                    $('#form_ubah input[name=username]').val(row.username);
                    $('#form_ubah select[name=role]').val(row.role_id).change();
                    $('#modal_ubah').modal('show');
                }
            },
        });
    }
</script>


<script>
    $('#select_data_apa').on('change', function() {
        var val = $(this).val();

        $('#select_rw').html("<option value='all'>--- All ---</option>");
        if (val == 'all') $('#select_kelurahan').html("<option value='all'>--- All ---</option>");

        let data = new FormData();
        data.append('data_apa', val);

        $.ajax({
            url: BASE_URL + 'select_data_apa',
            type: "POST",
            data: data,
            dataType: 'JSON',
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function() {
                // $('#loader_modal').fadeIn(300);
            },
            complete: function() {
                // $('#loader_modal').fadeOut(300);
            },
            success: function(data) {
                var data = data.data;
                var html = "<option value='all'>--- All ---</option>";

                for (var i = 0; i < data.length; i++) {
                    html += "<option value='" + data[i].KELURAHAN + "'>" + data[i].KELURAHAN + "</option>";
                }

                $("#select_data_apa").html(html);
            }
        });
    });
</script>