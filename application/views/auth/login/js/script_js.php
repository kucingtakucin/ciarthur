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
            loading();

            let formData = new FormData(form);

            formData.append(
                await csrf().then(csrf => csrf.token_name),
                await csrf().then(csrf => csrf.hash)
            )

            $('.invalid-feedback').fadeOut(500)
            $('.is-invalid').removeClass('is-invalid')
            axios.post(BASE_URL, formData)
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
                    console.log(err)
                    let errors = err.response.data.errors;
                    let html = err.response.data.message;
                    let title = err.response.data.title;

                    if (typeof errors === 'object') {
                        Object.entries(errors).map(([key, value]) => {
                            $(`#input_login_${key}`).addClass('is-invalid')
                            $(`#error_login_${key}`).html(value).fadeIn(500)
                        })
                        Swal.close();
                    } else if (title) {
                        errors.map(item => {
                            html += `<i class="fa fa-angle-right"></i> ${item.replaceAll('-', ' ')} <br>`
                        });
                        Swal.fire({
                            icon: 'error',
                            // title: err.response.statusText,
                            title: title ? title : "Oops...",
                            html: html,
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            // title: err.response.statusText,
                            title: title ? title : "Oops...",
                            html: html,
                        })
                    }
                }).then(res => {
                    grecaptcha.reset()
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