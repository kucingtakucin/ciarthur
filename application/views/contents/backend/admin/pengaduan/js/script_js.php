<script>
    $(() => {
        var pusher = new Pusher('21a14c9bc94b57c3db03', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('ciarthur-pengaduan-channel');
            channel.bind('ciarthur-pengaduan-event', function(data) {
            console.log(JSON.stringify(data));
            Swal.fire({
                title: 'Informasi', 
                icon: 'info', 
                text: data.message
            })
        });
    })
</script>