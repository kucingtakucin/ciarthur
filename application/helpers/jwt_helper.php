<?php

/**
 * JSON Web Token implementation, based on ci spec:
 * http://tools.ietf.org/html/draft-ietf-oauth-json-web-token-06
 *
 * PHP version 5
 *
 * @category Authentication
 * @package  Authentication_JWT
 * @author   Neuman Vong <neuman@twilio.com>
 * @author   Anant Narayanan <anant@php.net>
 * @license  http://opensource.org/licenses/BSD-3-Clause 3-clause BSD
 * @link     https://github.com/firebase/php-jwt
 */


/**
 * Decodes a JWT string into a PHP object.
 *
 * @param string      $jwt    The JWT
 * @param string|null $key    The secret key
 * @param bool        $verify Don't skip verification process 
 *
 * @return object      The JWT's payload as a PHP object
 * @throws UnexpectedValueException Provided JWT was invalid
 * @throws DomainException          Algorithm was not provided
 * 
 * @uses jsonDecode
 * @uses urlsafeB64Decode
 */
function jwt_decode($jwt, $key = null, $verify = true)
{
    $tks = explode('.', $jwt);
    if (count($tks) != 3) {
        //if you don't want to disclose more details
        return false;

        //throw new UnexpectedValueException('Wrong number of segments');
    }
    list($headb64, $bodyb64, $cryptob64) = $tks;
    if (null === ($header = jwt_jsonDecode(jwt_urlsafeB64Decode($headb64)))) {
        //if you don't want to disclose more details
        return false;

        //throw new UnexpectedValueException('Invalid segment encoding');
    }
    if (null === $payload = jwt_jsonDecode(jwt_urlsafeB64Decode($bodyb64))) {
        //if you don't want to disclose more details
        return false;

        //throw new UnexpectedValueException('Invalid segment encoding');
    }
    $sig = jwt_urlsafeB64Decode($cryptob64);
    if ($verify) {
        if (empty($header->alg)) {
            //if you don't want to disclose more details
            return false;

            //throw new DomainException('Empty algorithm');
        }
        if ($sig != jwt_sign("$headb64.$bodyb64", $key, $header->alg)) {
            throw new UnexpectedValueException('Signature verification failed');
        }
    }
    return $payload;
}

/**
 * Converts and signs a PHP object or array into a JWT string.
 *
 * @param object|array $payload PHP object or array
 * @param string       $key     The secret key
 * @param string       $algo    The signing algorithm. Supported
 *                              algorithms are 'HS256', 'HS384' and 'HS512'
 *
 * @return string      A signed JWT
 * @uses jsonEncode
 * @uses urlsafeB64Encode
 */
function jwt_encode($data, $key, $algo = 'HS256')
{
    $CI = &get_instance();
    $header = array('typ' => 'JWT', 'alg' => $algo);
    $payload = array(
        "iss" => $CI->config->item('iss'),
        "aud" => $CI->config->item('aud'),
        "iat" => $CI->config->item('iat'),
        "nbf" => $CI->config->item('nbf'),
        "exp" => $CI->config->item('exp'),
        "data" => $data
    );

    $segments = array();
    $segments[] = jwt_urlsafeB64Encode(jwt_jsonEncode($header));
    $segments[] = jwt_urlsafeB64Encode(jwt_jsonEncode($payload));
    $signing_input = implode('.', $segments);

    $signature = jwt_sign($signing_input, $key, $algo);
    $segments[] = jwt_urlsafeB64Encode($signature);

    $token = implode('.', $segments);

    $CI->db->update('tokens', [
        'is_active' => '0'
    ], ['is_active' => '1', 'user_id' => $data['id']]);

    $CI->db->insert("tokens", [
        "user_id" => $data["id"],
        "token" => $token,
        "level" => null,
        "ip_addresses" => $CI->input->ip_address(),
        "is_active" => '1',
        'created_at' => time()
    ]);

    return $token;
}

/**
 * Sign a string with a given key and algorithm.
 *
 * @param string $msg    The message to sign
 * @param string $key    The secret key
 * @param string $method The signing algorithm. Supported
 *                       algorithms are 'HS256', 'HS384' and 'HS512'
 *
 * @return string          An encrypted message
 * @throws DomainException Unsupported algorithm was specified
 */
function jwt_sign($msg, $key, $method = 'HS256')
{
    $methods = array(
        'HS256' => 'sha256',
        'HS384' => 'sha384',
        'HS512' => 'sha512',
    );
    if (empty($methods[$method])) {
        throw new DomainException('Algorithm not supported');
    }
    return hash_hmac($methods[$method], $msg, $key, true);
}

/**
 * Decode a JSON string into a PHP object.
 *
 * @param string $input JSON string
 *
 * @return object          Object representation of JSON string
 * @throws DomainException Provided string was invalid JSON
 */
function jwt_jsonDecode($input)
{
    $obj = json_decode($input);
    if (function_exists('json_last_error') && $errno = json_last_error()) {
        jwt_handleJsonError($errno);
    } else if ($obj === null && $input !== 'null') {
        throw new DomainException('Null result with non-null input');
    }
    return $obj;
}

/**
 * Encode a PHP object into a JSON string.
 *
 * @param object|array $input A PHP object or array
 *
 * @return string          JSON representation of the PHP object or array
 * @throws DomainException Provided object could not be encoded to valid JSON
 */
function jwt_jsonEncode($input)
{
    $json = json_encode($input);
    if (function_exists('json_last_error') && $errno = json_last_error()) {
        jwt_handleJsonError($errno);
    } else if ($json === 'null' && $input !== null) {
        throw new DomainException('Null result with non-null input');
    }
    return $json;
}

/**
 * Decode a string with URL-safe Base64.
 *
 * @param string $input A Base64 encoded string
 *
 * @return string A decoded string
 */
function jwt_urlsafeB64Decode($input)
{
    $remainder = strlen($input) % 4;
    if ($remainder) {
        $padlen = 4 - $remainder;
        $input .= str_repeat('=', $padlen);
    }
    return base64_decode(strtr($input, '-_', '+/'));
}

/**
 * Encode a string with URL-safe Base64.
 *
 * @param string $input The string you want encoded
 *
 * @return string The base64 encode of what you passed in
 */
function jwt_urlsafeB64Encode($input)
{
    return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
}

/**
 * Helper method to create a JSON error.
 *
 * @param int $errno An error number from json_last_error()
 *
 * @return void
 */
function jwt_handleJsonError($errno)
{
    $messages = array(
        JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
        JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
        JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON'
    );
    throw new DomainException(
        isset($messages[$errno])
            ? $messages[$errno]
            : 'Unknown JSON error: ' . $errno
    );
}

function validateTimestamp($token)
{
    $CI = &get_instance();
    try {
        $token_from_user = validateToken($token);
        if (!$token_from_user) {
            return (object)[
                'status' => false,
                'message' => "Token Not Found"
            ];
        }

        $check_in_db = $CI->db->get_where('tokens', [
            'user_id' => $token_from_user->data->id,
            'is_active' => '1'
        ])->row();

        if (!$check_in_db) {
            return (object)[
                'status' => false,
                'message' => "Token Unavailable"
            ];
        }

        if (time() - $check_in_db->created_at < $CI->config->item('exp')) {
            return (object)[
                'status' => true,
                'token' => $token_from_user
            ];
        }
        return (object)[
            'status' => false,
            'message' => "Token expired"
        ];
    } catch (Throwable $th) {
        return (object)[
            'status' => false,
            'message' => "Token mismatch",
            'exception' => $th->getMessage()
        ];
    }
}

function validateToken($token)
{
    $CI = &get_instance();
    return jwt_decode($token, $CI->config->item('key'));
}

function generateToken($data)
{
    $CI = &get_instance();
    return jwt_encode($data, $CI->config->item('key'));
}
