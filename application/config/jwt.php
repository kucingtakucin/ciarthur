<?php
// Store your secret key here
// Make sure you use better, long, more random key than this
$config['key'] = "388ce91b1a1257d7018a171e949aff3f7741f883b1045c6f1e96d8dccc3ee90b8c1bff9364e03ece"; // secret key
$config['iss'] = "appt.demoo.id"; // domain name
$config['aud'] = "appt.demoo.id"; // domain name
$config['iat'] = time(); // current time
// $config['nbf'] = $config['iat'] + 30; // not using before 30 sec
$config['exp'] = (1 * 60); // valid for 1 min after generate
