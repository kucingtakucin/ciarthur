<?php

use Kreait\Firebase\Factory;

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	/**
	 * MY_Controller constructor
	 */
	public function __construct()
	{
		parent::__construct();

		header('Access-Control-Allow-Origin: ' . base_url());
		header('Access-Control-Allow-Headers: *');
		header('Access-Control-Expose-Headers: *');
		header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE');

		session('csrf', csrf());
		$this->encryption->initialize(
			[
				'driver' => 'openssl',
				'cipher' => 'aes-256',
				'mode' => 'ctr',
			]
		);

		$factory = (new Factory)
			->withProjectId(config_item('firebase_project_id'))
			->withServiceAccount(config_item('firebase_service_account'))
			->withDatabaseUri(config_item('firebase_database_uri'));

		$this->auth = $factory->createAuth();
		$this->realtimeDatabase = $factory->createDatabase();
		$this->cloudMessaging = $factory->createMessaging();
		$this->remoteConfig = $factory->createRemoteConfig();
		$this->cloudStorage = $factory->createStorage();
		// $this->firestore = $factory->createFirestore();
	}
}
