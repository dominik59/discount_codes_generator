<?php
/**
 * Created by PhpStorm.
 * User: DominikP
 * Date: 24.05.2017
 * Time: 15:01
 */

namespace Modules;


class Encryptor {
	private $private_key = '-----BEGIN RSA PRIVATE KEY-----
MIIBPAIBAAJBALMy5YnhMXlCCKSl956a5iB0i8rZoRp4Ec+BfisyEqgAzdoHSD97
fa43cvWe1a0ZXg3GEcTRx2II3XGZblK2sf8CAwEAAQJAVZbrt41NmgMGKc3zlVea
rsm7syl7Hy9WImxRHMSP0JmydowVBJwdoMOu1OQeJ6yKMDQT2N8f6peh7tKfI3IT
MQIhAPuV3wT9iHt3zP/LQBGA49EIY9RLbuT4GHIdt0mXkvlTAiEAtlfc3Iw3VDfg
kMQ/PPxPk1yDn7i6ZzNT2QuU9MW6kyUCIQD1BWJ9xrosnWGe4gFUyrWVeFlZgdnP
z7xnL3+5gZCXlwIhAIKR/hyL517OOGdRr/rqrczW9YXdENWvgn4sdfik0kplAiEA
swxZxFBSTKTX0Ar+/DosWRseJCh29AjnOh61ByME8F8=
-----END RSA PRIVATE KEY-----';
	private $public_key = '-----BEGIN PUBLIC KEY-----
MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBALMy5YnhMXlCCKSl956a5iB0i8rZoRp4
Ec+BfisyEqgAzdoHSD97fa43cvWe1a0ZXg3GEcTRx2II3XGZblK2sf8CAwEAAQ==
-----END PUBLIC KEY-----';

	public function encrypt( $string ) {
		if ( openssl_public_encrypt( $string, $encrypted, $this->public_key ) ) {
			$data = $this->base64_url_encode( $encrypted );
		} else {
			throw new \Exception( 'Nie można zaszyfrować danych. Prawdopodobnie klucz publiczny jest zbyt krótki' );
		}

		return $data;
	}

	public function decrypt( $string ) {
		if ( openssl_private_decrypt( $this->base64_url_decode( $string ), $decrypted, $this->private_key ) ) {
			$data = $decrypted;
		} else {
			throw new \Exception( 'Nie można zdeszyfrować danych. Prawdopodobnie dane są uszkodzone' );
		}

		return $data;
	}

	function base64_url_encode($input) {
		return strtr(base64_encode($input), '+/=', '-_,');
	}

	function base64_url_decode($input) {
		return base64_decode(strtr($input, '-_,', '+/='));
	}
}