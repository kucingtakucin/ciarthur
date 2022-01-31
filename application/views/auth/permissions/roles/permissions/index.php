<div class="card">
	<div class="card-header">
		<h1>Manage Role Permissions</h1>
	</div>

	<div class="card-body">
		<form id="role_permissions" class="needs-validation" onsubmit="$role_permissions(event, '<?= urlencode($this->encryption->encrypt($group_id)) ?>')" novalidate>
			<div class="form-group">
				<table id="datatable-role-permissions" class="table table-striped table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th>Permission</th>
							<th>Allow</th>
							<th>Deny</th>
							<th>Ignore</th>
						</tr>
					</thead>
					<tbody>
						<?php $i = 0;
						if ($permissions) : ?>
							<?php foreach ($permissions as $k => $v) : ?>
								<tr>
									<td><?= ++$i ?></td>
									<td><?php echo $v['name']; ?></td>
									<td><?php echo form_radio("perm_{$v['id']}", '1', set_radio("perm_{$v['id']}", '1', (array_key_exists($v['key'], $group_permissions) && $group_permissions[$v['key']]['value'] === TRUE) ? TRUE : FALSE)); ?></td>
									<td><?php echo form_radio("perm_{$v['id']}", '0', set_radio("perm_{$v['id']}", '0', (array_key_exists($v['key'], $group_permissions) && $group_permissions[$v['key']]['value'] != TRUE) ? TRUE : FALSE)); ?></td>
									<td><?php echo form_radio("perm_{$v['id']}", 'X', set_radio("perm_{$v['id']}", 'X', (!array_key_exists($v['key'], $group_permissions)) ? TRUE : FALSE)); ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td colspan="4">There are currently no permissions to manage, please add some permissions</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<div class="form-group">
				<a href="<?= base_auth('permissions/role') ?>" class="btn btn-secondary btn-pill">Kembali</a>
				<?php echo form_submit('submit', 'Save Permission', 'class="btn btn-primary btn-pill"'); ?>
				<button class="btn btn-primary btn-pill loader" type="button" disabled style="display: none;">
					<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
					Loading...
				</button>
			</div>
		</form>
	</div>
</div>
