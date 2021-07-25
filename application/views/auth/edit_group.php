<div class="card">
      <div class="card-header">
            <h1><?php echo lang('edit_group_heading'); ?></h1>
            <p><?php echo lang('edit_group_subheading'); ?></p>
      </div>

      <div class="card-body">
            <div id="infoMessage" class="text-danger mb-3"><?php echo $message; ?></div>

            <?php echo form_open(current_url()); ?>

            <div class="form-group">
                  <?php echo lang('edit_group_name_label', 'group_name'); ?> <br />
                  <?php echo form_input($group_name, '', 'class="form-control"'); ?>
            </div>

            <div class="form-group">
                  <?php echo lang('edit_group_desc_label', 'description'); ?> <br />
                  <?php echo form_input($group_description, '', 'class="form-control"'); ?>
            </div>

            <div class="form-group">
                  <a href="<?= base_auth() ?>" class="btn btn-secondary btn-pill">Kembali</a>
                  <?php echo form_submit('submit', lang('edit_group_submit_btn'), 'class="btn btn-primary btn-pill"'); ?>
            </div>

            <?php echo form_close(); ?>
      </div>
</div>