<script>
    const BASE_URL = "<?= base_url($uri_segment) ?>"
    let $insert

    $(() => {
        /**
        * Keperluan store pengaduan
        */
        // ================================================== //
        $insert = async (form) => {
            Swal.fire({
                title: 'Apakah anda yakin untuk mengirim pengaduan?',
                text: "Pastikan data yang terisi sudah benar!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, kirim!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    loading()
        
                    let formData = new FormData(form)
                    formData.append(
                        await csrf().then(csrf => csrf.token_name),
                        await csrf().then(csrf => csrf.hash)
                    )

                    axios.post(BASE_URL + 'insert', formData)
                        .then(res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: res.data.message,
                                showConfirmButton: false,
                                timer: 1500
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
                            $('#form-pengaduan').trigger('reset');
                            $('#form-pengaduan').removeClass('was-validated')
                        })
                }
            })
        }

        $('#form-pengaduan').submit(function (event) {
            event.preventDefault()
            if (this.checkValidity()) {
                $insert(this)
            }
        })
    })
</script>