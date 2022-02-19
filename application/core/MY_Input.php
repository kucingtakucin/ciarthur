<?php

class MY_Input extends CI_Input
{
    /**
     * Fetch from array
     *
     * Internal method used to retrieve values from global arrays.
     *
     * @param	array	&$array		$_GET, $_POST, $_COOKIE, $_SERVER, etc.
     * @param	mixed	$index		Index for item to be fetched from $array
     * @param	bool	$xss_clean	Whether to apply XSS filtering
     * @return	mixed
     */
    protected function _fetch_from_array(&$array, $index = NULL, $xss_clean = NULL, $method = false)
    {
        is_bool($xss_clean) or $xss_clean = $this->_enable_xss;

        if (!$index) {
            $output = [];
            switch ($method) {
                case 'post':
                    // Max 4 levels
                    foreach ($_POST as $k => $v) {
                        if (is_array($v)) {
                            foreach ($v as $k2 => $v2) {
                                if (is_array($v2)) {
                                    foreach ($v2 as $k3 => $v3) {
                                        if (is_array($v3)) {
                                            foreach ($v3 as $k4 => $v4) {
                                                $output[$k][$k2][$k3][$k4] = $v4 && ((int)$v4 === 0) && is_string($v4) && !empty(json_decode($v4)) ? cryptojs_aes_decrypt($v4) : $v4;
                                            }
                                        } else $output[$k][$k2][$k3] = $v3 && ((int)$v3 === 0) && is_string($v3) && !empty(json_decode($v3)) ? cryptojs_aes_decrypt($v3) : $v3;
                                    }
                                } else {
                                    $output[$k][$k2] = $v2 && ((int)$v2 === 0) && is_string($v2) && !empty(json_decode($v2)) ? cryptojs_aes_decrypt($v2) : $v2;
                                }
                            }
                        } else $output[$k] = $v && ((int)$v === 0) && is_string($v) && !empty(json_decode($v)) ? cryptojs_aes_decrypt($v) : $v;
                    }

                    return $output;
                    break;
                case 'get':
                    return $_GET;
                    break;
                default:
                    # code...
                    break;
            }
        }
        // If $index is NULL, it means that the whole $array is requested
        isset($index) or $index = array_keys($array);

        // allow fetching multiple keys at once
        if (is_array($index)) {
            $output = array();
            foreach ($index as $key) {
                $output[$key] = $this->_fetch_from_array($array, $key, $xss_clean);
            }
            return $output;
        }

        if (isset($array[$index])) {
            $value = $array[$index];
            if (is_array($value)) {
                $output = [];
                // Max 3 levels
                foreach ($value as $k => $v) {
                    if (is_array($v)) {
                        foreach ($v as $k2 => $v2) {
                            if (is_array($v2)) {
                                foreach ($v2 as $k3 => $v3) {
                                    $output[$k][$k2][$k3] = $v3 && ((int)$v3 === 0) && is_string($v3) && !empty(json_decode($v3)) ? cryptojs_aes_decrypt($v3) : $v3;
                                }
                            } else $output[$k][$k2] = $v2 && ((int)$v2 === 0) && is_string($v2) && !empty(json_decode($v2)) ? cryptojs_aes_decrypt($v2) : $v2;
                        }
                    } else $output[$k] = $v && ((int)$v === 0) && is_string($v) && !empty(json_decode($v)) ? cryptojs_aes_decrypt($v) : $v;
                }

                return $output;
            }
        } elseif (($count = preg_match_all('/(?:^[^\[]+)|\[[^]]*\]/', $index, $matches)) > 1) // Does the index contain array notation
        {
            $value = $array;
            for ($i = 0; $i < $count; $i++) {
                $key = trim($matches[0][$i], '[]');
                if ($key === '') break;

                if (isset($value[$key])) $value = $value[$key];
                else return NULL;
            }
        } else return NULL;

        if ($method === 'post' && !is_array($value)) {
            return ($xss_clean)
                ? $this->security->xss_clean(($value && !empty(json_decode($value)) ? cryptojs_aes_decrypt($value) : $value))
                : ($value && !empty(json_decode($value)) ? cryptojs_aes_decrypt($value) : $value);
        }

        return ($xss_clean === TRUE)
            ? $this->security->xss_clean($value)
            : $value;
    }

    // --------------------------------------------------------------------

    /**
     * Fetch an item from the GET array
     *
     * @param	mixed	$index		Index for item to be fetched from $_GET
     * @param	bool	$xss_clean	Whether to apply XSS filtering
     * @return	mixed
     */
    public function get($index = NULL, $xss_clean = NULL)
    {
        return $this->_fetch_from_array($_GET, $index, $xss_clean, 'get');
    }

    // --------------------------------------------------------------------

    /**
     * Fetch an item from the POST array
     *
     * @param	mixed	$index		Index for item to be fetched from $_POST
     * @param	bool	$xss_clean	Whether to apply XSS filtering
     * @return	mixed
     */
    public function post($index = NULL, $xss_clean = NULL)
    {
        return $this->_fetch_from_array($_POST, $index, $xss_clean, 'post');
    }
}
