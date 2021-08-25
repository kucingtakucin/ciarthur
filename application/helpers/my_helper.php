<?php
function sistem()
{
    return (object) [
        "nama" => "Omahan CIARTHUR"
    ];
}

function sidebar_active($no, $menu)
{
    $ci = &get_instance();
    $uri = $ci->uri->segment($no);

    if ($uri == $menu) {
        return 'active';
    }
}

function redirect_to()
{
    $ci = &get_instance();
    if ($ci->ion_auth_model->in_group('admin')) {   // $ci->ion_auth->is_admin('admin')
        return base_admin('dashboard');
    } elseif ($ci->ion_auth_model->in_group('member')) {
        return base_member('dashboard');
    }
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

function in_group($group)
{
    $ci = &get_instance();
    return $ci->ion_auth_model->in_group($group);
}

function check_group($group)
{
    $ci = &get_instance();
    if (!$ci->ion_auth_model->in_group($group))
        return redirect('auth/login');
}

function base_auth($link = null)
{
    return base_url("auth/$link");
}

function base_admin($link = null)
{
    return base_url("backend/admin/$link");
}

function base_member($link = null)
{
    return base_url("backend/member/$link");
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
