<?php

namespace zsign;

class SignUtil{
	// CLASS INTENDED FOR FUTURE USE
	// NOT PART OF V1

	public static function validateFileFormats( array $files ){

		// what if new file format added ? remove check from here ?

		$supportedFormats = ["pdf", "jpg", "jpeg", "png", "doc", "docx","tex", "txt", "sxw", "odt", "rtf"];

		if( is_array( $files ) ){
			foreach ($files as $file) {
				if( !is_readable($file) && !in_array( pathinfo($file, PATHINFO_EXTENSION), $supportedFormats )  ){
					return false;
				}
				elseif( is_string($file) ){
					if( !is_readable($file) && !in_array( pathinfo($file, PATHINFO_EXTENSION), $supportedFormats )  ){
						return false;
					}
				}//curlfile support needed
				return true;
			}
		}
	}


}
