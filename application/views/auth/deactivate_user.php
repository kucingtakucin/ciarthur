<div class="card">
    <div class="card-header">
        <h1><?php echo lang('deactivate_heading'); ?></h1>
    </div>
    <div class="card-body">
        <p><?php echo sprintf(lang('deactivate_subheading'), $user->{$identity}); ?></p>
        <?php echo form_open("auth/deactivate/" . $user->id); ?>
        <div class="form-group form-check form-check-inline">
            <?php echo lang('deactivate_confirm_y_label', 'confirm_yes', 'class="form-check-label mr-1"'); ?>
            <input type="radio" name="confirm" value="yes" id="confirm_yes" class="form-check-input mr-3" checked="checked" />
            <?php echo lang('deactivate_confirm_n_label', 'confirm_no', 'class="form-check-label mr-1"'); ?>
            <input type="radio" name="confirm" value="no" id="confirm_no" class="form-check-input" />
        </div>

        <?php echo form_hidden($csrf); ?>
        <?php echo form_hidden(['id' => $user->id]); ?>

        <div class="form-group">
            <?php echo form_submit('submit', lang('deactivate_submit_btn'), 'class="btn btn-primary"'); ?>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>