<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 * 
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * <a href="http://www.owasp.org/index.php/ESAPI">http://www.owasp.org/index.php/ESAPI</a>.
 *
 * Copyright (c) 2007 - 2009 The OWASP Foundation
 * 
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 * 
 * @author 
 * @created 2008
 * @since 1.4
 * @package org.owasp.esapi.codecs
 */


/**
 * The Codec interface defines a set of methods for encoding and decoding application level encoding schemes,
 * such as HTML entity encoding and percent encoding (aka URL encoding). Codecs are used in output encoding
 * and canonicalization.  The design of these codecs allows for character-by-character decoding, which is
 * necessary to detect double-encoding and the use of multiple encoding schemes, both of which are techniques
 * used by attackers to bypass validation and bury encoded attacks in data.
 * 
 * @author 
 * @since 1.4
 * @see org.owasp.esapi.Encoder
 */
 abstract class Codec {

	/**
	 * Encode a String with a Codec
	 * 
	 * @param input
	 * 		the String to encode
	 * @return the encoded String
	 */
	abstract function encode( $immune, $input ); 
	
	/**
	 * Encode a Character with a Codec
	 * 
	 * @param c
	 * 		the Character to encode
	 * @return
	 * 		the encoded Character
	 */
	abstract function encodeCharacter( $immune, $c );
	
	/**
	 * Decode a String that was encoded using the encode method in this Class
	 * 
	 * @param input
	 * 		the String to decode
	 * @return
	 *		the decoded String
	 */
	abstract function decode( $input );
	

	/**
	 * Returns the decoded version of the next character from the input string and advances the
	 * current character in the PushbackString.  If the current character is not encoded, this 
	 * method MUST reset the PushbackString.
	 * 
	 * @param input	the Character to decode
	 * 
	 * @return the decoded Character
	 */
	abstract function decodeCharacter( $input );
	
		/**
		 * Lookup the hex value of any character that is not alphanumeric, return null if alphanumeric.
		 *
		 * @param c
		 * @return
		 */
		public static function getHexForNonAlphanumeric( $c ) 
		{
			
		        if ( chr(c) > 0xFF ) 
		        {
		        	return null;
		        }
		        
		        return $this->hex[c];
		}

	    /**
	     * Return the hex value of a character as a string without leading zeroes.
	     *
	     * @param c
	     * @return
	     */
        public static function toHex( $c ) {
			return dechex(chr(c));
        }

        /**
         * Utility to search a char[] for a specific char.
         * 
         * @param c
         * @param array
         * @return
         */
        public static function containsCharacter( $c, $array )
		{
                return in_array($c, $array);
        }
	
}