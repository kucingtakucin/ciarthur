<script>
    let csrf, login, loading;
    const BASE_URL = "<?= base_url($uri_segment) ?>"
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

    // Document ready
    $(() => {
        /**
         * Keperluan generate csrf
         */
        // ================================================== //
        csrf = async () => {
            let formData = new FormData()
            formData.append('key', '<?= $this->encryption->encrypt(bin2hex('csrf')) ?>')

            let res = await axios.post('<?= base_url('csrf/generate') ?>', formData)
            return {
                token_name: res.data.csrf_token_name,
                hash: res.data.csrf_hash
            }
        }

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
                        html: 'Something went wrong!',
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