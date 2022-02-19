<?php

use Nullix\CryptoJsAes\CryptoJsAes;
use Ramsey\Uuid\Uuid;

function sistem()
{
	return (object) [
		"nama" => config_item('sistem')
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

	$get = $ci->input->get($input, $xss_clean);
	if (!$get) return null;
	return $get;
}

function post($input = null, $xss_clean = true)
{
	$ci = &get_instance();

	if (!$input) return $ci->input->post();

	$post = $ci->input->post($input, $xss_clean);
	if (!$post) return null;
	return $post;
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

function cryptojs_aes_encrypt($original_value)
{
	$password = config_item('cryptojs_aes_password');
	$encrypted = CryptoJsAes::encrypt($original_value, $password);
	return $encrypted;
}

function cryptojs_aes_decrypt($encrypted)
{
	$password = config_item('cryptojs_aes_password');
	$decrypted = CryptoJsAes::decrypt($encrypted, $password);
	return $decrypted;
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

function http_request($method = 'GET', $url = '', $body = [], $headers = [])
{

	$curl = curl_init();

	curl_setopt_array($curl, [
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => $method,
		CURLOPT_POSTFIELDS => $body,
		CURLOPT_HTTPHEADER => count($headers) ? $headers : [
			'Accept: application/json'
		],
	]);

	$response = curl_exec($curl);

	curl_close($curl);
	return $response;
}

function upload($name = 'foto', $path = './uploads/', $type = 'single', Closure $callback = null)
{
	$ci = &get_instance();

	$config['upload_path'] = $path;
	$config['allowed_types'] = 'jpg|jpeg|png';
	$config['max_size'] = 2048;
	$config['encrypt_name'] = true;
	$config['remove_spaces'] = true;
	$ci->upload->initialize($config);

	if ($type === 'single') :

		if (!$ci->upload->do_upload($name))
			response([
				'status' => false,
				'message' => $ci->upload->display_errors('', ''),
				'files' => $_FILES,
				'payload' => post()
			], 404);

		return $ci->upload->data('file_name');

	elseif ($type === 'multiple') :

		foreach ($_FILES[$name]['name'] as $k => $v) {
			$_FILES['file']['name'] = $v['name'][$k];
			$_FILES['file']['type'] = $v['type'][$k];
			$_FILES['file']['tmp_name'] = $v['tmp_name'][$k];
			$_FILES['file']['error'] = $v['error'][$k];
			$_FILES['file']['size'] = $v['size'][$k];

			// Upload file to server
			if ($ci->upload->do_upload($name)) {
				// Uploaded file data
				$file_data = $ci->upload->data();

				if (is_callable($callback)) $callback($k, $file_data);
			}
		}
	endif;
}
