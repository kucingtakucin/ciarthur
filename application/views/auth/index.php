<div class="card">
	<div class="card-header">
		<h5 class="text-center">Kelola Data User</h5>
		<p class="text-muted text-center mb-0">Sistem Informasi <?= sistem()->nama ?></p>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div id="infoMessage"><?php echo $message; ?></div>
		<div class="row mb-3">
			<div class="col-md-12">
				<!-- <button type="button" class="btn btn-primary btn-pill float-right" id="tombol_tambah"><i class="fa fa-plus"></i> Tambah Data</button> -->
				<?php echo anchor('auth/create_user', lang('index_create_user_link'), 'class="btn btn-primary btn-pill float-right ml-3"') ?>
				<?php echo anchor('auth/create_group', lang('index_create_group_link'), 'class="btn btn-primary btn-pill float-right"') ?>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<table id="table_data" class="table table-striped table-hover">
					<thead>
						<tr>
							<th><?php echo lang('index_fname_th'); ?></th>
							<th><?php echo lang('index_lname_th'); ?></th>
							<th><?php echo lang('index_email_th'); ?></th>
							<th><?php echo lang('index_groups_th'); ?></th>
							<th><?php echo lang('index_status_th'); ?></th>
							<th><?php echo lang('index_action_th'); ?></th>
						</tr>
					</thead>
					<tbody id="table_body">
						<?php foreach ($users as $user) : ?>
							<tr>
								<td><?php echo htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8'); ?></td>
								<td><?php echo htmlspecialchars($user->last_name, ENT_QUOTES, 'UTF-8'); ?></td>
								<td><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?></td>
								<td>
									<?php foreach ($user->groups as $group) : ?>
										<?php echo anchor("auth/edit_group/" . $group->id, htmlspecialchars($group->name, ENT_QUOTES, 'UTF-8'), 'class="badge badge-primary"'); ?>
									<?php endforeach ?>
								</td>
								<td><?php echo ($user->active) ? anchor("auth/deactivate/" . $user->id, lang('index_active_link'), 'class="badge badge-success"') : anchor("auth/activate/" . $user->id, lang('index_inactive_link'), 'class="badge badge-danger"'); ?></td>
								<td><?php echo anchor("auth/edit_user/" . $user->id, 'Edit', 'class="btn btn-pill btn-primary"'); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>