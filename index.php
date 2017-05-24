<?php
/**
 * Created by PhpStorm.
 * User: wikid
 * Date: 23.05.2017
 * Time: 21:17
 */
require_once './vendor/autoload.php';
set_time_limit(30000);
$generator = new \Controller\Generator();
//print_r(json_encode($generator->generate_discount_codes(10, 5,70,'AABB')));
if ( is_array( $_POST ) && count( $_POST ) > 0 ) {

	if ( isset( $_POST['how_many'] ) && is_numeric( $_POST['how_many'] ) && $_POST['how_many'] !== '' ) {
		$how_many = (int) $_POST['how_many'];
	} else {
		$generator->parse_error_page( 'Brak sprecyzowanej ilości kodów lub podany ciąg nie jest w formacie numerycznym' );

		return - 1;
	}
	if ( isset( $_POST['length'] ) && is_numeric( $_POST['length'] ) && $_POST['length'] !== '' ) {
		$length = (int) $_POST['length'];
	} else {
		$generator->parse_error_page( 'Brak sprecyzowanej długości kodu lub podany ciąg nie jest w formacie numerycznym' );

		return - 1;
	}
	if ( isset( $_POST['percentage_of_fill'] ) && $_POST['percentage_of_fill'] !== '' ) {
		if ( is_numeric( $_POST['percentage_of_fill'] ) && ( (int) $_POST['percentage_of_fill'] ) >= 1 && ( (int) $_POST['percentage_of_fill'] ) <= 99 ) {
			$percentage_of_fill = (int) $_POST['percentage_of_fill'];
		} else {
			$generator->parse_error_page( 'Podany procent wypełnienia nie jest w formacie numerycznym' );

			return - 1;
		}
	} else {
		$percentage_of_fill = 70;
	}

	if ( isset( $_POST['custom_characters'] ) && $_POST['custom_characters'] !== '' ) {
		$custom_characters = $_POST['custom_characters'];
	} else {
		$custom_characters = null;
	}
	$generator->download_generated_discount_codes( $how_many, $length, $percentage_of_fill, $custom_characters );
	//print_r( json_encode( $generator->download_generated_discount_codes( $how_many, $length, $percentage_of_fill, $custom_characters ) ) );

} else {
	$generator->parse_page_to_generate_codes();
}

