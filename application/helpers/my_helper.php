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
