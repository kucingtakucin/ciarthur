<?php

class MY_Security extends CI_Security
{

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }

    /**
     * Show CSRF Error
     *
     * @return	void
     */
    public function csrf_show_error()
    {
        if (
            !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
        ) {
            http_response_code(403);
            echo json_encode([
                'status' => false,
                'message' => 'The action you have requested is not allowed.',
                'data' => null,
            ]);
            exit;
        } else show_error('The action you have requested is not allowed.', 403);
    }

    /**
     * CSRF Verify
     *
     * @return	CI_Security
     */
    public function csrf_verify()
    {
        // If it's not a POST request we will set the CSRF cookie
        if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
            return $this->csrf_set_cookie();
        }

        // Check if URI has been whitelisted from CSRF checks
        if ($exclude_uris = config_item('csrf_exclude_uris')) {
            $uri = load_class('URI', 'core');
            foreach ($exclude_uris as $excluded) {
                if (preg_match('#^' . $excluded . '$#i' . (UTF8_ENABLED ? 'u' : ''), $uri->uri_string())) {
                    return $this;
                }
            }
        }

        if (
            !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
        ) {
            $x_xsrf_token = (array) json_decode(@$_SERVER['HTTP_X_XSRF_TOKEN']);
            $csrf_hash = @$x_xsrf_token[bin2hex('hash')];

            $valid = isset($_SERVER['HTTP_X_XSRF_TOKEN'], $_COOKIE[$this->_csrf_cookie_name])
                && is_string($csrf_hash) && is_string($_COOKIE[$this->_csrf_cookie_name])
                && hash_equals(base64_decode($csrf_hash), $_COOKIE[$this->_csrf_cookie_name]);
        } else {
            // Check CSRF token validity, but don't error on mismatch just yet - we'll want to regenerate
            $valid = isset($_POST[$this->_csrf_token_name], $_COOKIE[$this->_csrf_cookie_name])
                && is_string($_POST[$this->_csrf_token_name]) && is_string($_COOKIE[$this->_csrf_cookie_name])
                && hash_equals($_POST[$this->_csrf_token_name], $_COOKIE[$this->_csrf_cookie_name]);

            // We kill this since we're done and we don't want to pollute the _POST array
            unset($_POST[$this->_csrf_token_name]);
        }

        // Regenerate on every submission?
        if (config_item('csrf_regenerate')) {
            // Nothing should last forever
            unset($_COOKIE[$this->_csrf_cookie_name]);
            $this->_csrf_hash = NULL;
        }

        $this->_csrf_set_hash();
        $this->csrf_set_cookie();

        if ($valid !== TRUE) {
            $this->csrf_show_error();
        }

        log_message('info', 'CSRF token verified');
        return $this;
    }
}
