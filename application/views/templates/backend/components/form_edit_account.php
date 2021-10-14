<form id="form_edit_account" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <div class="row m-0">

            <div class="col-sm-3 text-left">
                <label class="mt-2" for="input_ubah_username">Username</label>
            </div>
            <div class="col-sm-9">
                <input type="text" id="input_ubah_username" name="username" class="form-control" placeholder="Masukkan Username" autocomplete="off" onkeydown="return event.keyCode != 13;">
                <div class="invalid-feedback text-danger text-left" style="display: none;" id="error_ubah_username"></div>
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="row m-0">
            <div class="col-sm-3 text-left">
                <label class="mt-2" for="select_ubah_password">Password</label>
            </div>
            <div class="col-sm-9">
                <input type="password" id="input_ubah_password" name="password" class="form-control" placeholder="Masukkan Password" autocomplete="off" onkeydown="return event.keyCode != 13;">
                <div class="invalid-feedback text-danger text-left" style="display: none;" id="error_ubah_password"></div>
            </div>
        </div>
    </div>
</form>