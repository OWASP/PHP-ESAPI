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
 * @author Linden Darling <a href="http://www.jds.net.au">JDS Australia</a>
 * @created 2009 
 * @since 1.6
 */

require_once(dirname(__FILE__) . '/CodecDebug.php');
require_once(dirname(__FILE__) . '/../Encoder.php');

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
		// debug
		CodecDebug::getInstance()->addUnencodedString(self::normalizeEncoding($input));
		
		$encoding =  self::detectEncoding($input);
		$mbstrlen = mb_strlen($input, $encoding);
		$encodedString = mb_convert_encoding("", $encoding);
		for($i=0; $i<$mbstrlen; $i++)
		{
			$c = mb_substr($input, $i, 1, $encoding);
			$encodedString .= $this->encodeCharacter($immune, $c);
		}

		// debug
		CodecDebug::getInstance()->output($encodedString);

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
		
		$initialEncoding = self::detectEncoding($c);
		
		// Start with nothing; format it to match the encoding of the string passed as an argument.
		$encodedOutput = mb_convert_encoding("", $initialEncoding);
		
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
    //TODO: need to add comments
    
		// Normalize string to UTF-32
		$_4ByteString = self::normalizeEncoding($input);
		
		// debug
		CodecDebug::getInstance()->addEncodedString($_4ByteString);

		// Start with an empty string.
		$decodedString = '';
		$targetCharacterEncoding = 'ASCII';
		
		//logic to iterate through the string's characters, while(input has characters remaining){}
		//feed whole sequence into decoder, which then determines the first decoded character from the input and "pushes back" the encodedPortion of seuquence and the resultant decodedCharacter to here
		while(mb_strlen($_4ByteString,"UTF-32") > 0)
		{
			// get the first decodedCharacter, allowing decodeCharacter to eat away at the string
			$decodeResult = $this->decodeCharacter($_4ByteString);	//decodeCharacter() returns an array containing 'decodedCharacter' and 'encodedString' so as to provide PushbackString-(from-ESAPI-JAVA)-like behaviour
			
			$decodedCharacter = $decodeResult['decodedCharacter']; //note: decodedCharacter should be UTF-32 encoded already
			
			$encodedString = $decodeResult['encodedString'];
			
			if ($decodedCharacter !== null)
			{
				// decodedCharacter is not null, so add it to the decodedString
				// and remove the encodedString portion from start of input
				// string set of characters matched an entity or numeric
				// encoding, so use the decoded character.
				if ($decodedCharacter != '')
				{
					list(, $ordinalValue) = unpack('N', $decodedCharacter);
					if ($ordinalValue >= 0x00 && $ordinalValue <= 0x7F)
					{
						// An ASCII character can be appended to a string of any
						// character encoding
						$decodedString .= mb_convert_encoding($decodedCharacter,
							'ASCII', "UTF-32"
						);
					}
					else if ($ordinalValue <= 0x10FFFF)
					{
						// convert the decoded character to UTF-8
						$decodedCharacterUTF8 = mb_convert_encoding(
							$decodedCharacter, 'UTF-8', 'UTF-32'
						);
						// convert decodedString to UTF-8 if necessary
						if (	$decodedString !== ''
							&&	$targetCharacterEncoding != 'UTF-8'
						) {
							$decodedString = mb_convert_encoding(
								$decodedString, 'UTF-8', $targetCharacterEncoding
							);
						}
						
						// now append the character to the string
						$decodedString .= $decodedCharacterUTF8;
						
						// see if decodedString can exist in
						// targetCharacterEncoding and if so, convert back to
						// it. Otherwise the target character encoding is
						// changed to 'UTF-8'
						if (	$targetCharacterEncoding != 'UTF-8'
							&&	$targetCharacterEncoding === mb_detect_encoding(
								$decodedString, $targetCharacterEncoding, true)
						) {
							// we can convert back to target encoding
							$decodedString = mb_convert_encoding(
								$decodedString, $targetCharacterEncoding, 'UTF-8'
							);
						} else {
							// decoded String now contains characters that are
							// UTF-8
							$targetCharacterEncoding = 'UTF-8';
						}
					}
					else if ($ordinalValue > 0x110000)
					{
						// Invalid codepoint
						$decodedString .= mb_convert_encoding(mb_substr($_4ByteString,0,1,"UTF-32"),$targetCharacterEncoding,"UTF-32");
						$_4ByteString = mb_substr($_4ByteString,1,mb_strlen($_4ByteString,"UTF-32"),"UTF-32");
						continue;
					}
				}

				// eat the encodedString portion off the start of the UTF-32 converted input string
				$_4ByteString = mb_substr($_4ByteString,mb_strlen($encodedString,"UTF-32"),mb_strlen($_4ByteString,"UTF-32"),"UTF-32");
			}
			else
			{
				// decodedCharacter is null, so add the single, unencoded
				// character to the decodedString and remove the 1st character
				// from the start of the input string.
				
				// character did not match an entity or numeric encoding in context of trailing characters, so use the character
				$decodedString .= mb_convert_encoding(mb_substr($_4ByteString,0,1,"UTF-32"),$targetCharacterEncoding,"UTF-32");
				
				// eat the single, unencoded character portion off the start of the UTF-32 converted input string
				$_4ByteString = mb_substr($_4ByteString,1,mb_strlen($_4ByteString,"UTF-32"),"UTF-32");
			}
		}
		
		// debug
		CodecDebug::getInstance()->output($decodedString);

		return $decodedString;
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
	   return $input;
	}
	
		/**
		 * Lookup the hex value of any character that is not alphanumeric, return null if alphanumeric.
		 *
		 * @param c
		 * @return
		 */
		public static function getHexForNonAlphanumeric( $c ) 
		{
		/* //original code from esapi java put in place in r152, breaks current implementation of HTMLEntityCodec however
			$ordinalValue = ord($c);
			if ( $ordinalValue > 255 ) return null;
			return self::$hex[$ordinalValue];
		*/
			
      		// Assumption/prerequisite: $c is a UTF-32 encoded string
			$_4ByteString = $c;
			
			// Grab the 4 byte character.
			$_4ByteCharacter = self::forceToSingleCharacter($_4ByteString);
			
			// Get the ordinal value of the character.
			list(, $ordinalValue) = unpack("N", $_4ByteCharacter);
			
			if ($ordinalValue <= 255)
			{
				return self::$hex[$ordinalValue];
			}
			return self::toHex($ordinalValue);
		}

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
          // Assumption/prerequisite: $c is a UTF-32 encoded single character
    			$_4ByteCharacter = $c;
    		
    			// Get the ordinal value of the character.
      			list(, $ordinalValue) = unpack("N", $_4ByteCharacter);
    			
    			foreach($array as $arrayCharacter)
    	  		{
    	  			// Convert to UTF-32 (4 byte characters, regardless of actual number of bytes in the character).
    	  			$_4ByteArrayCharacter = self::normalizeEncoding($arrayCharacter);
    	  			
    	  			// Ensure it's a single 4 byte character (since $array is an array of strings) by grabbing only the 1st multi-byte character.
    	  			$_4ByteArrayCharacter = self::forceToSingleCharacter($_4ByteArrayCharacter);
    	  			
    	  			// If the character is contained in the array then return it.
    	  			if($_4ByteCharacter === $_4ByteArrayCharacter)
    	  			{
    	  				return true;
    	  			}
    	  		}
    	  		
    	  		return false;
        }

        /**
         * Utility to detect a (potentially multibyte) string's encoding with extra logic to deal with single characters that mb_detect_encoding() fails upon.
         *
         * @param string
         * @return detectedEncoding
         */

        public static function detectEncoding($string)
        {
            // detect encoding, special-handling for chr(172) and chr(128) to chr(159) which fail to be detected by mb_detect_encoding()
            $is_single_byte = false;
            try {
                $bytes = unpack('C*', $string);
                if (is_array($bytes) && sizeof($bytes, 0) == 1) {
                    $is_single_byte = true;
                }
            } catch (Exception $e) {
                // unreach?
                ESAPI::getLogger('Codec')->warn(DefaultLogger::SECURITY, false, 'Codec::detectEncoding threw an exception whilst attempting to unpack an input string', $e);
            }
            
            if ($is_single_byte === false)
            {
                // NoOp
            }
            else if ((ord($string) == 172)  || (ord($string) >= 128 && ord($string) <= 159))
            {
                return 'ASCII'; //although these chars are beyond ASCII range, if encoding is forced to ISO-8859-1 they will all encode to &#x31;
            }
            else if(ord($string) >= 160 && ord($string) <= 255)
            {
                return 'ISO-8859-1';
            }
             
            // Strict encoding detection with fallback to non-strict detection.
            if (mb_detect_encoding($string, 'UTF-32', true))
            {
                return 'UTF-32';
            }
            else if (mb_detect_encoding($string, 'UTF-16', true))
            {
                return 'UTF-16';
            }
            else if (mb_detect_encoding($string, 'UTF-8', true))
            {
                return 'UTF-8';
            }
            else if (mb_detect_encoding($string, 'ISO-8859-1', true))
            {
                // To try an catch strings containing mixed encoding, search
                // the string for chars of ordinal in the range 128 to 159 and
                // 172 and don't return ISO-8859-1 if present.
                $limit = mb_strlen($string, 'ISO-8859-1');
                for ($i=0; $i<$limit; $i++)
                {
                    $char = mb_substr($string, $i, 1, 'ISO-8859-1');
                    if (   (ord($char) == 172)
                        || (ord($char) >= 128 && ord($char) <= 159)
                    ) {
                        return 'UTF-8';
                    }
                }
                return 'ISO-8859-1';
            }
            else if (mb_detect_encoding($string, 'ASCII', true))
            {
                return 'ASCII';
            }
            else
            {
                return mb_detect_encoding($string);
            }
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
			$initialEncoding = self::detectEncoding($string);
			
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
		
    /***
     * Utility method to determine if a single character string is a hex digit
     * 
     * @param c
     * 						Single character string that is potentially a hex digit
     * 
     * @return
     * 						boolean. True indicates that the single character string is a hex digit
     */
    function isHexDigit($c)
    {
    	// Assumption/prerequisite: $c is a UTF-32 encoded single character
    	$_4ByteCharacter = $c;
    	
    	// Get the ordinal value of the character.
		  list(, $ordinalValue) = unpack("N", $_4ByteCharacter);
		
    	// if character is a hex digit, return true
    	if(preg_match("/^[0-9a-fA-F]/",chr($ordinalValue)))
    	{
    		return true;
    	}
    	
    	return false;
    }
}