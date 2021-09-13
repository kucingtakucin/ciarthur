<script>
    let csrf, login, loading;
    const BASE_URL = "<?= base_url($uri_segment) ?>"\

    // Document ready
    $(() => {

        /**
         * Keperluan show loading
         */
        // ================================================== //
        loading = () => {
            Swal.fire({
                title: 'Loading...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            })
        }

        /**
         * Keperluan login
         */
        // ================================================== //

        login = async (form) => {
            if (!grecaptcha.getResponse()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: "Recaptcha wajib dicentang!",
                })
                return;
            }

            loading();

            let formData = new FormData(form);
            formData.append(
                await csrf().then(csrf => csrf.token_name),
                await csrf().then(csrf => csrf.hash)
            )

            axios.post(BASE_URL + 'login', formData)
                .then(res => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: res.data.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                    setTimeout(() => {
                        location.replace(res.data.redirect)
                    }, 1000);
                }).catch(err => {
                    console.log(err)
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        // html: err.response.data.message,
                        html: 'Something went wrong!'
                    })
                }).then(res => {
                    $('#form-login').removeClass('was-validated')
                })
        }

        $('#form-login').submit(function(event) {
            event.preventDefault()
            if (this.checkValidity()) {
                login(this);
            }
        })
    })
</script>