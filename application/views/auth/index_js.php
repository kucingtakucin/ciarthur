<script>
    let csrf, login;
    const BASE_URL = "<?= base_url($uri_segment) ?>"

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

            fetch(BASE_URL + 'login', {
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
                setTimeout(() => {
                    location.replace(response.redirect)
                }, 1000);
            }).catch(error => {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: "Ada masalah saat login!",
                    showConfirmButton: false,
                    timer: 2000
                })
            }).finally(() => {
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