<script>
    $(() => {
        $('.chat-history').animate({ scrollTop: $(document).height() }, 1500);

        let kiri = `
        <li>
            <div class="message my-message"><img class="rounded-circle float-left chat-user-img img-30" src="https://upload.wikimedia.org/wikipedia/commons/0/0a/Gnome-stock_person.svg" alt="">
                <div class="message-data text-right"><span class="message-data-time">10:12 am</span></div>                                                            Are we meeting today? Project has been already finished and I have results to show you.
            </div>
        </li>
        `

        let kanan = `
        <li class="clearfix">
            <div class="message other-message pull-right"><img class="rounded-circle float-right chat-user-img img-30" src="https://upload.wikimedia.org/wikipedia/commons/0/0a/Gnome-stock_person.svg" alt="">
                <div class="message-data"><span class="message-data-time">10:14 am</span></div>                                                            Well I am not sure. The rest of the team is not here yet. Maybe in an hour or so?
            </div>
        </li>
        `
    })
</script>