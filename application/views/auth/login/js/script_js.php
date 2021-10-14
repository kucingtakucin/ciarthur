<script>
    let login, status_crud = false;
    const BASE_URL = "<?= base_url($uri_segment) ?>"

    // Document ready
    $(() => {
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

            axios.post(BASE_URL + 'index', formData)
                .then(res => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: res.data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.replace("<?= base_url() ?>" + res.data.redirect)
                    })
                }).catch(err => {
                    let errors = err.response.data.message;
                    let html = '';
                    console.log(typeof errors)
                    if (typeof errors === 'object') {
                        errors.map(item => {
                            html += `<i class="fa fa-angle-right"></i> ${item.replaceAll('-', ' ')} <br>`
                        });
                    } else {
                        html = errors
                    }
                    Swal.fire({
                        icon: 'error',
                        title: err.response.statusText,
                        html: html,
                    })
                }).then(res => {
                    grecaptcha.reset()
                    $('#form-login').removeClass('was-validated')
                })
        }

        $('#form-login').submit(function(event) {
            event.preventDefault()
            if (this.checkValidity()) {
                login(this);
            }
        })

        // ================================================== //
    })
</script>