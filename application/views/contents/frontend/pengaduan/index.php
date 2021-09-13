<div class="wrapper light-wrapper">
    <div class="container inner">
        <h2 class="section-title">Pengaduan</h2>
        <p class="lead larger">Have any questions? Reach out to us from our contact form and we will get back to you shortly.</p>
        <div class="space40"></div>
        
        <div class="row">
            <div class="col-lg-7">
                <form id="pengaduan-form" class="fields-white needs-validation" method="post" novalidate>
                    <div class="messages"></div>
                    <div class="controls">
                        <div class="form-row">
                            <div class="col-lg-12 col-xl-12">
                                <div class="form-group">
                                    <input id="form_name" type="text" name="name" class="form-control" placeholder="Nama Lengkap *" required data-error="Nama lengkap wajib diisi">
                                    <?= validation_feedback("nama lengkap", "wajib diisi") ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-lg-12 col-xl-6">
                                <div class="form-group">
                                    <input id="form_email" type="email" name="email" class="form-control" placeholder="Email *" required data-error="Email wajib diisi dan harus valid">
                                    <?= validation_feedback("email", "wajib diisi dan harus valid") ?> 
                               </div>
                            </div>
                            <div class="col-lg-12 col-xl-6">
                                <div class="form-group">
                                    <input id="form_phone" type="tel" name="phone" class="form-control" placeholder="Phone">
                                    <?= validation_feedback("nomor telepon", "wajib diisi") ?>                                
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea id="form_message" name="message" class="form-control" placeholder="Message *" rows="4" required data-error="Pesan wajib diisi"></textarea>
                                    <?= validation_feedback("pesan", "wajib diisi") ?>                                
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-send">Send message</button>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12">
                                <p class="text-muted"><strong>*</strong> These fields are required.</p>
                            </div>
                        </div>
                    </div>
                </form>
            <!-- /form -->
            </div>
        
        <!--/column -->
            <div class="space30 d-none d-md-block d-lg-none"></div>
            
            <div class="col-lg-4 offset-lg-1">
                <div class="d-flex flex-row">
                    <div>
                        <div class="icon color-default fs-34 mr-25"> <i class="icofont-location-pin"></i> </div>
                    </div>
                    <div>
                        <h6 class="mb-5">Address</h6>
                        <address>Moonshine St. 14/05 Light City, <br class="d-none d-md-block" />London, United Kingdom</address>
                    </div>
                </div>
                <div class="d-flex flex-row">
                    <div>
                        <div class="icon color-default fs-34 mr-25"> <i class="icofont-telephone"></i> </div>
                    </div>
                    <div>
                        <h6 class="mb-5">Phone</h6>
                        <p>00 (123) 456 78 90 <br class="d-none d-md-block" />00 (987) 654 32 10</p>
                    </div>
                </div>
                <div class="d-flex flex-row">
                    <div>
                        <div class="icon color-default fs-34 mr-25"> <i class="icofont-mail-box"></i> </div>
                    </div>
                    <div>
                        <h6 class="mb-5">E-mail</h6>
                        <p><a href="mailto:snowlake@email.com" class="nocolor">snowlake@email.com</a> <br class="d-none d-md-block" /><a href="mailto:help@snowlake.com" class="nocolor">help@snowlake.com</a></p>
                    </div>
                </div>
            </div>
          <!--/column -->
        </div>
        <!--/.row -->
    </div>
      <!-- /.container -->
</div>
    <!-- /.wrapper -->