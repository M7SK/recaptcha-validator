<?php
namespace m7\RecaptchaValidator;

use Exception;

class Validator {
	const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';
	
	/**
	 * $secret
	 *
	 * @var string
	 */
	protected $secret;

	public function __construct($secret) {
		$this->secret = $secret;
	}

	public function validate($challenge = null) {
		if (!$challenge) {
			$challenge = $_POST['g-recaptcha-response'];
		}

		if (!$challenge) {
			throw new Exception('missing_recaptcha_challenge');
		}
	
		$options = [
			'http' => [
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => http_build_query([
					'secret' => $this->secret,
					'response' => $_POST['g-recaptcha-response'],
					'remoteip' => $_SERVER['REMOTE_ADDR'],
				]),
			],
		];

		$response = file_get_contents(self::VERIFY_URL, false, stream_context_create($options));

		if (!$response) {
			throw new Exception('no_response');
		}

		$result = json_decode($response);

		if (!$result->success) {
			throw new Exception('verification_unsuccessful');
		}
	}
}
