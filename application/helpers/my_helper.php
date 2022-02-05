<?php

use Ramsey\Uuid\Uuid;

function sistem()
{
	return (object) [
		"nama" => "Omahan CIARTHUR"
	];
}

function sidebar_active($segment, $menu)
{
	$ci = &get_instance();
	$uri = $ci->uri->segment($segment);

	if ($uri == $menu) {
		return 'active';
	}
}

function total_segments()
{
	$ci = &get_instance();

	return $ci->uri->total_segments();
}

function logged_in()
{
	$ci = &get_instance();
	return $ci->ion_auth->logged_in();
}

function user()
{
	$ci = &get_instance();
	return $ci->ion_auth_model->user()->row();
}

function get_user_id()
{
	$ci = &get_instance();
	return $ci->ion_auth->get_user_id();
}

function is_admin()
{
	$ci = &get_instance();
	return $ci->ion_auth->is_admin();
}

function in_role($role)
{
	$ci = &get_instance();
	return $ci->ion_auth_model->in_group($role);
}

function role($role)
{
	if ($role === 'guest' && logged_in()) : redirect('backend/dashboard');
	elseif ($role !== 'guest' && !logged_in()) : redirect('~/login');
	else : return;
	endif;

	$ci = &get_instance();
	if (!$ci->ion_auth_model->in_group($role))
		if ($ci->input->is_ajax_request() || $ci->input->is_cli_request()) {
			response([
				'status' => false,
				'message' => "You must be an $role to access this resource",
				'data' => null
			], 403);
		} else show_error("You must be an $role to access this resource", 403, "Forbidden");
}

function get_role()
{
	$ci = &get_instance();
	$ci->db->select('name');
	$ci->db->from('roles');
	$ci->db->where('id = (SELECT role_id FROM role_user WHERE user_id = ' . get_user_id() . ')');
	$result =  @$ci->db->get()->row()->name;
	return $result;
}

function has_permission($permission_key)
{
	if (!logged_in()) {
		redirect('~/login');
	}

	$ci = &get_instance();
	if (!$ci->ion_auth_acl->has_permission($permission_key)) {
		if ($ci->input->method() === 'get') :

			if ($ci->input->is_ajax_request() || $ci->input->is_cli_request()) {
				response([
					'status' => false,
					'message' => "Access Denied. You don't have permission to access this resource",
					'data' => null
				], 403);
			} else show_error("Access Denied. You don't have permission to access this resource", 403, 'Forbidden');

		elseif ($ci->input->method() === 'post') :

			if ($ci->input->is_ajax_request() || $ci->input->is_cli_request()) {
				response([
					'status' => false,
					'message' => "Access Denied. You don't have permission to access this resource",
					'data' => null
				], 403);
			} else show_error("Access Denied. You don't have permission to access this resource", 403, 'Forbidden');

		endif;
	}
}

function is_allowed($permission_key)
{
	$ci = &get_instance();
	return $ci->ion_auth_acl->is_allowed($permission_key);
}

function is_denied($permission_key)
{
	$ci = &get_instance();
	return $ci->ion_auth_acl->is_denied($permission_key);
}

function method($method)
{
	$ci = &get_instance();
	if ($ci->input->method() !== strtolower($method)) {
		if ($ci->input->is_ajax_request() || $ci->input->is_cli_request()) {
			response([
				'status' => false,
				'message' => 'Method Not Allowed',
				'data' => null
			], 403);
		} else show_error("Method Not Allowed", 403, 'Forbidden');
	}
}

function response($payload = [], $status = 200)
{
	$ci = &get_instance();
	$ci->output->set_content_type('application/json', 'utf-8');
	$ci->output->set_status_header($status);
	$ci->output->set_output(json_encode($payload));
	$ci->output->_display();
	exit();
}

function render($data)
{
	$ci = &get_instance();
	$ci->templates->render($data);
}

function view($view = '', $data = [], $return = false)
{
	$ci = &get_instance();
	return $ci->load->view($view, $data, $return);
}

function get($input = null, $xss_clean = true)
{
	$ci = &get_instance();

	if (!$input) return $ci->input->get();
	return $ci->input->get($input, $xss_clean);
}

function post($input = null, $xss_clean = true)
{
	$ci = &get_instance();

	if (!$input) return $ci->input->post();
	return $ci->input->post($input, $xss_clean);
}

function session($key = null, $value = null)
{
	$ci = &get_instance();

	if (!$key) return $_SESSION;
	elseif (!$value) return $ci->session->userdata($key);

	return $ci->session->set_userdata($key, $value);
}

function uuid()
{
	return Uuid::uuid4()->toString();
}

function now()
{
	return date('Y-m-d H:i:s');
}

function dd($var)
{
	highlight_string("<?php\n\n" . var_export($var, true) . ";\n\n?>");
	exit;
}

function csrf()
{
	$ci = &get_instance();
	return [
		'token_name' => $ci->security->get_csrf_token_name(),
		'hash' => $ci->security->get_csrf_hash(),
		'cookie' => @$_COOKIE[config_item('cookie_prefix') . config_item('csrf_cookie_name')]
	];
}

function base_auth($link = null)
{
	return base_url("auth/$link");
}

function validation_feedback($type, $field)
{
	return "
        <div class=\"invalid-feedback text-danger text-left\" style=\"display: none;\" id=\"error_{$type}_{$field}\"></div>
    ";
}

function validation_tooltip($type, $field)
{
	return "
        <div class=\"invalid-tooltip text-white text-left\" style=\"display: none;\" id=\"error_{$type}_{$field}\"></div>
    ";
}

function recaptcha_display()
{
	$ci = &get_instance();
	return $ci->recaptcha->create_box();
}

function recaptcha_render_js()
{
	return "<script src=\"https://www.google.com/recaptcha/api.js\" async defer></script>";
}

/**
 * Simple method to encrypt or decrypt a plain text string
 * initialization vector(IV) has to be the same when encrypting and decrypting
 * 
 * @param string $action: can be 'encrypt' or 'decrypt'
 * @param string $string: string to encrypt or decrypt
 *
 * @return string
 */
function encrypt_decrypt($action, $string)
{
	$output = false;

	$encrypt_method = "AES-256-CBC";
	$secret_key = bin2hex('secret_key');
	$secret_iv = bin2hex('secret_iv');

	// hash
	$key = hash('sha256', $secret_key);

	// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	$iv = substr(hash('sha256', $secret_iv), 0, 16);

	if ($action === 'encrypt') {
		$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
		$output = base64_encode($output);
	} else if ($action === 'decrypt') {
		$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	}

	return $output;
}

function slugify($string)
{
	// Get an instance of $this
	$CI = &get_instance();

	$CI->load->helper('text');
	$CI->load->helper('url');

	// Replace unsupported characters (add your owns if necessary)
	$string = str_replace("'", '-', $string);
	$string = str_replace(".", '-', $string);
	$string = str_replace("²", '2', $string);

	// Slugify and return the string
	return url_title(convert_accented_characters($string), 'dash', true);
}
