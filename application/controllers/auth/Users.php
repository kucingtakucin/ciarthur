<?php

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class User
 */
class Users extends MY_Controller
{
	public $data = [];
	private $_path = 'auth/users/';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(['form_validation']);
		$this->load->helper(['url', 'language']);

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');

		role('admin');
	}

	/**
	 * Redirect if needed, otherwise display the user list
	 */
	public function index()
	{
		$this->templates->render([
			'title' => 'Users',
			'type' => 'backend',
			'breadcrumb' => [
				'Auth', 'Manajemen', 'Users'
			],
			'uri_segment' => $this->_path,
			'page' => $this->_path . 'index',
			'script' => $this->_path . 'js/script.js.php',
			'style' => $this->_path . 'css/style.css.php',
			'modals' => []
		]);
	}

	public function edit_account()
	{
		$this->form_validation->set_rules('username', 'username', 'required|trim');
		$this->form_validation->set_rules('password', 'password', 'required|trim|min_length[8]');
		if (!$this->form_validation->run()) {
			return $this->output->set_content_type('application/json')
				->set_status_header(422)
				->set_output(json_encode([
					'status' => false,
					'message' => 'Please check your input again!',
					'errors' => $this->form_validation->error_array()
				]));
		}

		$this->db->update('users', [
			'username' => $this->input->post('username'),
			'password' => $this->ion_auth->hash_password($this->input->post('password'))
		], ['id' => $this->input->post('id')]);

		return $this->output->set_content_type('application/json')
			->set_output(json_encode([
				'status' => true,
				'message' => 'Berhasil mengupdate account'
			]));
	}

	public function data_user()
	{
		$datatables = new Datatables(new CodeigniterAdapter());
		$datatables->query(
			"SELECT a.id, a.username, a.email, a.active, a.created_on,
			(SELECT b.role_id FROM role_user AS b WHERE b.user_id = a.id) AS role_id,
			(SELECT c.name FROM roles AS c WHERE c.id = role_id) AS nama_role
			FROM users AS a"
		);

		// Add row index
		$datatables->add('DT_RowIndex', function () {
			return 0;
		});

		return $this->output->set_content_type('application/json')
			->set_output($datatables->generate());
	}

	/**
	 * Create a new user
	 */
	public function create_user()
	{
		$this->data['title'] = $this->lang->line('create_user_heading');

		$identity_column = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;

		if ($this->input->method() === 'post') {
			$email = strtolower($this->input->post('email'));
			$identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
			$password = $this->input->post('password');

			$additional_data = [
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'company' => $this->input->post('company'),
				'phone' => $this->input->post('phone'),
			];

			if ($this->ion_auth->register($identity, $password, $email, $additional_data)) {
				// check to see if we are creating the user
				// redirect them back to the admin page
				// $this->session->set_flashdata('message', $this->ion_auth->messages());
				// redirect("auth", 'refresh');
				return $this->output->set_content_type('application/json')
					->set_output(json_encode([
						'status' => true,
						'message' => 'User berhasil ditambahkan',
						'data' => null
					]));
			}
			return $this->output->set_content_type('application/json')
				->set_status_header(404)
				->set_output(json_encode([
					'status' => false,
					'message' => 'Gagal',
					'data' => null,
					'errors' => $this->ion_auth->messages(),
				]));
		} elseif ($this->input->method() === 'get') {
			// display the create user form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['first_name'] = [
				'name' => 'first_name',
				'id' => 'first_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('first_name'),
			];
			$this->data['last_name'] = [
				'name' => 'last_name',
				'id' => 'last_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('last_name'),
			];
			$this->data['identity'] = [
				'name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
			];
			$this->data['email'] = [
				'name' => 'email',
				'id' => 'email',
				'type' => 'text',
				'value' => $this->form_validation->set_value('email'),
			];
			$this->data['company'] = [
				'name' => 'company',
				'id' => 'company',
				'type' => 'text',
				'value' => $this->form_validation->set_value('company'),
			];
			$this->data['phone'] = [
				'name' => 'phone',
				'id' => 'phone',
				'type' => 'text',
				'value' => $this->form_validation->set_value('phone'),
			];
			$this->data['password'] = [
				'name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password'),
			];
			$this->data['password_confirm'] = [
				'name' => 'password_confirm',
				'id' => 'password_confirm',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
			];

			// $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'create_user', $this->data);
			$this->templates->render([
				'title' => $this->data['title'],
				'type' => 'backend',
				'identity_column' => $this->data['identity_column'],
				'breadcrumb' => [
					'Auth', 'Manajemen', 'Users', 'Create'
				],
				'first_name' => $this->data['first_name'],
				'last_name' => $this->data['last_name'],
				'identity' => $this->data['identity'],
				'email' => $this->data['email'],
				'company' => $this->data['company'],
				'phone' => $this->data['phone'],
				'password' => $this->data['password'],
				'password_confirm' => $this->data['password_confirm'],
				'message' => $this->data['message'],
				'uri_segment' => $this->_path,
				'page' => $this->_path . 'create_user',
				'script' => $this->_path . 'js/script.js.php',
				'style' => $this->_path . 'css/style.css.php',
				'modals' => []
			]);
		}
	}


	/**
	 * Edit a user
	 *
	 * @param int|string $id
	 */
	public function edit_user($id)
	{
		$this->data['title'] = $this->lang->line('edit_user_heading');

		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id))) {
			redirect('auth', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();
		$groups = $this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result_array();

		//USAGE NOTE - you can do more complicated queries like this
		//$groups = $this->ion_auth->where(['field' => 'value'])->groups()->result_array();

		if ($this->input->method() === 'post') {
			// do we have a valid request?
			// if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
			// 	show_error($this->lang->line('error_csrf'));
			// }

			$data = [
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'company' => $this->input->post('company'),
				'phone' => $this->input->post('phone'),
			];

			// update the password if it was posted
			if ($this->input->post('password')) {
				$data['password'] = $this->input->post('password');
			}

			// Only allow updating groups if user is admin
			if ($this->ion_auth->is_admin()) {
				// Update the groups user belongs to
				$this->ion_auth->remove_from_group('', $id);

				$groupData = $this->input->post('groups');
				if (isset($groupData) && !empty($groupData)) {
					foreach ($groupData as $grp) {
						$this->ion_auth->add_to_group($grp, $id);
					}
				}
			}

			// check to see if we are updating the user
			if ($this->ion_auth->update($user->id, $data)) {
				// redirect them back to the admin page if admin, or to the base url if non admin
				// $this->session->set_flashdata('message', $this->ion_auth->messages());
				// $this->redirectUser();
				return $this->output->set_content_type('application/json')
					->set_output(json_encode([
						'status' => true,
						'message' => 'User berhasil diubah',
						'data' => null
					]));
			}
			// redirect them back to the admin page if admin, or to the base url if non admin
			// $this->session->set_flashdata('message', $this->ion_auth->errors());
			// $this->redirectUser();
			return $this->output->set_content_type('application/json')
				->set_status_header(404)
				->set_output(json_encode([
					'status' => true,
					'message' => "Gagal",
					'data' => null,
					'errors' => $this->ion_auth->errors()
				]));
		} elseif ($this->input->method() == 'get') {
			// display the edit user form
			// $this->data['csrf'] = $this->_get_csrf_nonce();

			// pass the user to the view
			$this->data['user'] = $user;
			$this->data['groups'] = $groups;
			$this->data['currentGroups'] = $currentGroups;

			$this->data['first_name'] = [
				'name'  => 'first_name',
				'id'    => 'first_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('first_name', $user->first_name),
			];
			$this->data['last_name'] = [
				'name'  => 'last_name',
				'id'    => 'last_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('last_name', $user->last_name),
			];
			$this->data['company'] = [
				'name'  => 'company',
				'id'    => 'company',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('company', $user->company),
			];
			$this->data['phone'] = [
				'name'  => 'phone',
				'id'    => 'phone',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('phone', $user->phone),
			];
			$this->data['password'] = [
				'name' => 'password',
				'id'   => 'password',
				'type' => 'password'
			];
			$this->data['password_confirm'] = [
				'name' => 'password_confirm',
				'id'   => 'password_confirm',
				'type' => 'password'
			];

			// $this->_render_page('auth/edit_user', $this->data);
			$this->templates->render([
				'title' => $this->data['title'],
				'type' => 'backend',
				'breadcrumb' => [
					'Auth', 'Manajemen', 'Users', 'Edit'
				],
				'first_name' => $this->data['first_name'],
				'last_name' => $this->data['last_name'],
				'company' => $this->data['company'],
				'phone' => $this->data['phone'],
				'password' => $this->data['password'],
				'password_confirm' => $this->data['password_confirm'],
				'user' => $this->data['user'],
				'groups' => $this->data['groups'],
				'currentGroups' => $this->data['currentGroups'],
				'uri_segment' => $this->_path,
				'page' => $this->_path . 'edit_user',
				'script' => $this->_path . 'js/script.js.php',
				'style' => $this->_path . 'css/style.css.php',
				'modals' => []
			]);
		}
	}

	public function delete_user()
	{
		if ($this->input->method() === 'post') {
			if ($this->ion_auth->delete_user($this->input->post('id'))) {
				return $this->output->set_content_type('application/json')
					->set_output(json_encode([
						'status' => true,
						'message' => 'User berhasil dihapus',
						'data' => null
					]));
			}

			return $this->output->set_content_type('application/json')
				->set_output(json_encode([
					'status' => false,
					'message' => 'Gagal',
					'data' => null,
					'errors' => $this->ion_auth->errors()
				]));
		}
		redirect('auth', 'refresh');
	}

	/**
	 * Change password
	 */
	public function change_password()
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() === FALSE) {
			// display the form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = [
				'name' => 'old',
				'id' => 'old',
				'type' => 'password',
			];
			$this->data['new_password'] = [
				'name' => 'new',
				'id' => 'new',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			];
			$this->data['new_password_confirm'] = [
				'name' => 'new_confirm',
				'id' => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			];
			$this->data['user_id'] = [
				'name' => 'user_id',
				'id' => 'user_id',
				'type' => 'hidden',
				'value' => $user->id,
			];

			// render
			// $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'change_password', $this->data);
		} else {
			$identity = $this->session->userdata('identity');

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change) {
				//if the password was successfully changed
				// $this->session->set_flashdata('message', $this->ion_auth->messages());
				// $this->logout();
				return $this->output->set_content_type('application/json')
					->set_output(json_encode([
						'status' => true,
						'message' => 'Password berhasil diubah',
						'data' => null,
					]));
			} else {
				// $this->session->set_flashdata('message', $this->ion_auth->errors());
				// redirect('auth/change_password', 'refresh');
				return $this->output->set_content_type('application/json')
					->set_output(json_encode([
						'status' => true,
						'message' => 'Gagal',
						'data' => null,
						'errors' => $this->ion_auth->errors()
					]));
			}
		}
	}

	/**
	 * Forgot password
	 */
	public function forgot_password()
	{
		$this->data['title'] = $this->lang->line('forgot_password_heading');

		// setting validation rules by checking whether identity is username or email
		if ($this->config->item('identity', 'ion_auth') != 'email') {
			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
		} else {
			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		}


		if ($this->form_validation->run() === FALSE) {
			$this->data['type'] = $this->config->item('identity', 'ion_auth');
			// setup the input
			$this->data['identity'] = [
				'name' => 'identity',
				'id' => 'identity',
			];

			if ($this->config->item('identity', 'ion_auth') != 'email') {
				$this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
			} else {
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			// set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			// $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'forgot_password', $this->data);
		} else {
			$identity_column = $this->config->item('identity', 'ion_auth');
			$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

			if (empty($identity)) {

				if ($this->config->item('identity', 'ion_auth') != 'email') {
					$this->ion_auth->set_error('forgot_password_identity_not_found');
				} else {
					$this->ion_auth->set_error('forgot_password_email_not_found');
				}

				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}

			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten) {
				// if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			} else {
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}

	/**
	 * Reset password - final step for forgotten password
	 *
	 * @param string|null $code The reset code
	 */
	public function reset_password($code = NULL)
	{
		if (!$code) {
			show_404();
		}

		$this->data['title'] = $this->lang->line('reset_password_heading');

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user) {
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() === FALSE) {
				// display the form

				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = [
					'name' => 'new',
					'id' => 'new',
					'type' => 'password',
					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				];
				$this->data['new_password_confirm'] = [
					'name' => 'new_confirm',
					'id' => 'new_confirm',
					'type' => 'password',
					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				];
				$this->data['user_id'] = [
					'name' => 'user_id',
					'id' => 'user_id',
					'type' => 'hidden',
					'value' => $user->id,
				];
				// $this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				// render
				// $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'reset_password', $this->data);
			} else {
				$identity = $user->{$this->config->item('identity', 'ion_auth')};

				// do we have a valid request?
				// if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) {

				// 	// something fishy might be up
				// 	$this->ion_auth->clear_forgotten_password_code($identity);

				// 	show_error($this->lang->line('error_csrf'));
				// } else {
				// finally change the password
				$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

				if ($change) {
					// if the password was successfully changed
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					redirect("auth/login", 'refresh');
				} else {
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					redirect('auth/reset_password/' . $code, 'refresh');
				}
				// }
			}
		} else {
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	/**
	 * Activate the user
	 *
	 * @param int         $id   The user ID
	 * @param string|bool $code The activation code
	 */
	public function activate($id, $code = FALSE)
	{
		$activation = FALSE;


		if ($this->input->method() === 'post') {
			if ($code !== FALSE) {
				$this->ion_auth->activate($id, $code);
			} else if ($this->ion_auth->is_admin()) {
				$this->ion_auth->activate($id);
			}
			// redirect them to the auth page
			// $this->session->set_flashdata('message', $this->ion_auth->messages());
			// redirect("auth", 'refresh');
			return $this->output->set_content_type('application/json')
				->set_output(json_encode([
					'status' => true,
					'message' => 'Status berhasil diubah',
					'data' => null,
				]));
		} else {
			// redirect them to the forgot password page
			// $this->session->set_flashdata('message', $this->ion_auth->errors());
			// redirect("auth/forgot_password", 'refresh');
			return $this->output->set_content_type('application/json')
				->set_status_header(404)
				->set_output(json_encode([
					'status' => false,
					'message' => 'Gagal',
					'data' => null,
					'errors' => $this->ion_auth->errors()
				]));
		}
	}

	/**
	 * Deactivate the user
	 *
	 * @param int|string|null $id The user ID
	 */
	public function deactivate($id = NULL)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			// redirect them to the home page because they must be an administrator to view this
			show_error('You must be an administrator to view this page.');
		}

		$id = (int)$id;

		if ($this->input->method() === 'get') {
			// insert csrf check
			// $this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();
			$this->data['identity'] = $this->config->item('identity', 'ion_auth');

			// $this->_render_page('auth' . DIRECTORY_SEPARATOR . 'deactivate_user', $this->data);
			$this->templates->render([
				'title' => 'Deactivate User',
				'type' => 'backend',
				'breadcrumb' => [
					'Auth', 'Manajemen', 'Users', 'Deactivate'
				],
				'user' => $this->data['user'],
				'identity' => $this->data['identity'],
				'uri_segment' => $this->_path,
				'page' => $this->_path . 'deactivate_user',
				'script' => $this->_path . 'js/script.js.php',
				'style' => $this->_path . 'css/style.css.php',
				'modals' => []
			]);
		} elseif ($this->input->method() === 'post') {
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes') {
				// do we have a valid request?				
				// if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
				// 	show_error($this->lang->line('error_csrf'));
				// }

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
					$this->ion_auth->deactivate($id);
				}

				return $this->output->set_content_type('application/json')
					->set_output(json_encode([
						'status' => true,
						'message' => 'Status berhasil diubah',
						'data' => null,
					]));
			}

			// redirect them back to the auth page
			// redirect('auth', 'refresh');
		}
	}
}
