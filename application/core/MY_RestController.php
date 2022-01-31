<?php

use chriskacerguis\RestServer\RestController;

class MY_RestController extends RestController
{

	public function __construct()
	{
		parent::__construct();
	}

	public function verify(Closure $callback)
	{
		if (
			array_key_exists('Authorization', $this->input->request_headers()) &&
			!empty($this->input->request_headers()['Authorization'])
		) {
			$headers = explode(" ", $this->input->request_headers()["Authorization"]);
			$bearer_token = end($headers);
			$decodedToken = validateTimestamp($bearer_token);

			// return response if token is valid
			if ($decodedToken->status) {
				// $this->set_response($decodedToken, RestController::HTTP_OK);
				if (is_callable($callback)) {
					$callback($decodedToken, $bearer_token);
				}
			} else {
				return $this->response([
					'status' => false,
					'message' => $decodedToken->message,
					'exception' => @$decodedToken->exception
				], RestController::HTTP_BAD_REQUEST);
			}
		}

		$this->response([
			'status' => false,
			'message' => "Unauthorized"
		], RestController::HTTP_UNAUTHORIZED);
	}

	public function verify_v2()
	{
		if (
			!array_key_exists('Authorization', $this->input->request_headers()) &&
			empty($this->input->request_headers()['Authorization'])
		) {
			$this->response([
				'status' => false,
				'message' => "Unauthorized"
			], RestController::HTTP_UNAUTHORIZED);
		}

		$headers = explode(" ", $this->input->request_headers()["Authorization"]);
		$bearer_token = end($headers);
		$decodedToken = validateTimestamp($bearer_token);

		// return response if token is valid
		if (!$decodedToken->status) {
			// $this->set_response($decodedToken, RestController::HTTP_OK);
			$this->response([
				'status' => false,
				'message' => $decodedToken->message,
				'exception' => @$decodedToken->exception
			], RestController::HTTP_BAD_REQUEST);
		}
	}
}
