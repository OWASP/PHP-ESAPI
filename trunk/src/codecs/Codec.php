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

  private static $hex = Array();
	
	public function __construct()
	{
		for ( $i = 0; $i < 256; $i++ )
		{
			if ( ($i >= 48 && $i <= 57) || ($i >= 65 && $i <= 90) || ($i >= 97 && $i <= 122) )
			{
				self::$hex[$i] = null;
			}
			else
			{
				self::$hex[$i] = self::toHex($i);
			}
		}
	}

	/**
	 * Encode a String with a Codec
	 * 
	 * @param input
	 * 		the String to encode
	 * @return the encoded String
	 */
	public function encode( $immune, $input )
	{
    $encoding =  mb_detect_encoding($input);
		$mbstrlen = mb_strlen($input);
		$encodedString = mb_convert_encoding("", $encoding);
		for($i=0; $i<$mbstrlen; $i++)
		{
			$c = mb_substr($input, $i, 1, $encoding);
			$encodedString .= $this->encodeCharacter($immune, $c);
		}
		return $encodedString;
  }
	
	/**
	 * Encode a Character with a Codec
	 * 
	 * @param c
	 * 		the Character to encode
	 * @return
	 * 		the encoded Character
	 */
	public function encodeCharacter( $immune, $c )
	{
    // Normalize string to UTF-32
		$_4ByteString = self::normalizeEncoding($c);
		
		// Start with nothing; format it to match the encoding of the string passed as an argument.
		$encodedOutput = mb_convert_encoding("", mb_detect_encoding($c));
		
		// Grab the 4 byte character.
		$_4ByteCharacter = self::forceToSingleCharacter($_4ByteString);
		
		// Get the ordinal value of the character.
		list(, $ordinalValue) = unpack("N", $_4ByteCharacter);
		
		return $encodedOutput.chr($ordinalValue);
  }
	
	/**
	 * Decode a String that was encoded using the encode method in this Class
	 * 
	 * @param input
	 * 		the String to decode
	 * @return
	 *		return null if null, otherwise return the decoded String
	 */
	function decode( $input )
	{
		
	}
	

	/**
	 * Returns the decoded version of the next character from the input string and advances the
	 * current character in the PushbackString.  If the current character is not encoded, this 
	 * method MUST reset the PushbackString.
	 * 
	 * @param input	the Character to decode
	 * 
	 * @return the decoded Character
	 */
	function decodeCharacter( $input )
	{
	
	}
	
		/**
		 * Lookup the hex value of any character that is not alphanumeric, return null if alphanumeric.
		 *
		 * @param c
		 * @return
		 */
		public static function getHexForNonAlphanumeric( $c ) 
		{
			$ordinalValue = ord($c);
			if ( $ordinalValue > 255 ) return null;
			return self::$hex[$ordinalValue];
/*			
			
      // Assumption/prerequisite: $c is a UTF-32 encoded string
			$_4ByteString = $c;
			
			// Grab the 4 byte character.
			$_4ByteCharacter = self::forceToSingleCharacter($_4ByteString);
			
			// Get the ordinal value of the character.
			list(, $ordinalValue) = unpack("N", $_4ByteCharacter);
			
			if ( $ordinalValue > 255 )
			{
				return null;
			}
			return self::$hex[$ordinalValue];
*/		}

	    /**
	     * Return the hex value of a character as a string without leading zeroes.
	     *
	     * @param c
	     * @return
	     */
      public static function toHex( $c )
      {
        // Assumption/prerequisite: $c is the ordinal value of the character (i.e. an integer)
        return dechex($c);
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

    /**
     * Utility to normalize a string's encoding to UTF-32.
     * 
     * @param string
     * @return
     */
		public static function normalizeEncoding($string)
		{
			// Convert to UTF-32 (4 byte characters, regardless of actual number of bytes in the character).
			//handle chr(172) and chr(128) to chr(159) which fail to be detected by mb_detect_encoding()
			if((ord($string) == 172)  || (ord($string) >= 128 && ord($string) <= 159))
			{
				$initialEncoding = "ASCII"; //although these chars are beyond ASCII range, if encoding is forced to ISO-8859-1 they will all encode to &#x31;
			}
			else if(ord($string) >= 160 && ord($string) <= 255)
			{
				$initialEncoding = "ISO-8859-1";
			}
			else
			{
				$initialEncoding = mb_detect_encoding($string);
			}
			
			$encoded = mb_convert_encoding($string,"UTF-32", $initialEncoding);
			
			return $encoded;
		}
		
    /**
     * Utility to get first (potentially multibyte) character from a (potentially multicharacter) multibyte string.
     * 
     * @param string
     * @return
     */
		public static function forceToSingleCharacter($string)
		{
			// Grab first character from UTF-32 encoded string
			return mb_substr($string, 0, 1, "UTF-32");
		}
}