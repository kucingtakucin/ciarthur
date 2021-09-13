<script>
    const BASE_URL = "<?= base_url($uri_segment) ?>"
    let $store_pengaduan

    $(() => {
        /**
        * Keperluan kirim pengaduan
        */
        // ================================================== //
        $store_pengaduan = async (form) => {
            let formData = new FormData(form)
            formData.append(
                await csrf().then(csrf => csrf.token_name),
                await csrf().then(csrf => csrf.hash)
            )

            axios.post(BASE_URL + 'coba', formData)
                .then(res => {
                    console.log(res)
                })
        }

        $('#contact-form').submit(function (event) {
            event.preventDefault()
            if (this.checkValidity()) {
                $store_pengaduan(this)
            }
        })
    })
</script>