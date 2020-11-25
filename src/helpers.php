<?php

if (!function_exists('validateRecaptcha')) {
	function validateRecaptcha($secret, $challenge) {
		try {
			assertValidRecaptcha($secret, $challenge);
			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
}

if (!function_exists('assertValidRecaptcha')) {
	function assertValidRecaptcha($secret, $challenge) {
		$validator = new m7\RecaptchaValidator\Validator($secret);
		$validator->validate($challenge);
	}
}
