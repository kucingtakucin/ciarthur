<?php
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
    if (!logged_in()) {
        redirect('auth/login');
    }

    $ci = &get_instance();
    if (!$ci->ion_auth_model->in_group($role))
        if ($ci->input->is_ajax_request() || $ci->input->is_cli_request()) {
            $ci->output->set_content_type('application/json')
                ->set_status_header(403);
            echo json_encode([
                'status' => false,
                'message' => "You must be an $role to access this resource",
                'data' => null
            ]);
            exit;
        } else {
            show_error("You must be an $role to access this resource", 403, "Forbidden");
        }
}

function has_permission($permission_key)
{
    if (!logged_in()) {
        redirect('auth/login');
    }

    $ci = &get_instance();
    if (!$ci->ion_auth_acl->has_permission($permission_key)) {
        if ($ci->input->method() === 'get') {
            if ($ci->input->is_ajax_request() || $ci->input->is_cli_request()) {
                $ci->output->set_content_type('application/json')
                    ->set_status_header(403);
                echo json_encode([
                    'status' => false,
                    'message' => "Access Denied. You don't have permission to access this resource",
                    'data' => null
                ]);
                exit;
            } else {
                // return redirect('auth/login');
                show_error("Access Denied. You don't have permission to access this resource", 403, 'Forbidden');
                // $ci->load->view('errors/html/error_403');
                // exit();
            }
        } elseif ($ci->input->method() === 'post') {
            if ($ci->input->is_ajax_request() || $ci->input->is_cli_request()) {
                $ci->output->set_content_type('application/json')
                    ->set_status_header(403);
                echo json_encode([
                    'status' => false,
                    'message' => "Access Denied. You don't have permission to access this resource",
                    'data' => null
                ]);
                exit;
            } else {
                show_error("Access Denied. You don't have permission to access this resource", 403, 'Forbidden');
            }
        }
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
            $ci->output->set_content_type('application/json')
                ->set_status_header(403);
            echo json_encode([
                'status' => false,
                'message' => 'Method Not Allowed',
                'data' => null
            ]);
            exit;
        } else {
            show_error("Method Not Allowed", 403, 'Forbidden');
        }
    }
}

function base_auth($link = null)
{
    return base_url("auth/$link");
}

function validation_feedback($field, $message)
{
    $field = ucwords($field);
    return "
    <div class=\"invalid-feedback text-danger\">$field $message</div>
    <div class=\"valid-feedback text-success\">Looks good</div>
    ";
}

function validation_tooltip($field, $message)
{
    $field = ucwords($field);
    return "
    <div class=\"invalid-tooltip\">$field $message</div>
    <div class=\"valid-tooltip\">Looks good</div>
    ";
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
