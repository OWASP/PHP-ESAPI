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
    
		$initialEncoding = mb_detect_encoding($input);
		
		// Normalize string to UTF-32
		$_4ByteString = self::normalizeEncoding($input);
		
		// Start with nothing; format it to match the encoding of the string passed as an argument.
		$decodedString = mb_convert_encoding("", $initialEncoding);
		
		//logic to iterate through the string's characters, while(input has characters remaining){}
		//feed whole sequence into decoder, which then determines the first decoded character from the input and "pushes back" the encodedPortion of seuquence and the resultant decodedCharacter to here
		while(mb_strlen($_4ByteString,"UTF-32") > 0)
		{
			// get the first decodedCharacter, allowing decodeCharacter to eat away at the string
			$decodeResult = $this->decodeCharacter($_4ByteString);	//decodeCharacter() returns an array containing 'decodedCharacter' and 'encodedString' so as to provide PushbackString-(from-ESAPI-JAVA)-like behaviour
			
			$decodedCharacter = $decodeResult['decodedCharacter']; //note: decodedCharacter should be UTF-32 encoded already
			
			$encodedString = $decodeResult['encodedString'];
			
			//FIXME: this isn't handling potential double-encodings
			if($decodedCharacter != null)
			{
				//decodedCharacter is not null, so add it to the decodedString and remove the encodedString portion from start of input string
				// set of characters matched an entity or numeric encoding, so use the decoded character
				if($decodedCharacter===";"
					|| $decodedCharacter==="!"
					|| $decodedCharacter==="@"
					|| $decodedCharacter==="$"
					|| $decodedCharacter==="%"
					|| $decodedCharacter==="("
					|| $decodedCharacter===")"
					|| $decodedCharacter==="="
					|| $decodedCharacter==="+"
					|| $decodedCharacter==="{"
					|| $decodedCharacter==="}"
					|| $decodedCharacter==="["
					|| $decodedCharacter==="]")	//TODO: widened but could be more and neater...TODO: prolly need to widen this akin to the special-case handling in encoding methods, since EncoderTest line 324 fails
				{
					//special case handling for semicolon since mb_convert_encoding fails for it
					$decodedString .= $decodedCharacter;
				}
				else
				{
					$decodedString .= mb_convert_encoding($decodedCharacter,$initialEncoding,"UTF-32");
				}				
				
				// eat the encodedString portion off the start of the UTF-32 converted input string
				$_4ByteString = mb_substr($_4ByteString,mb_strlen($encodedString,"UTF-32"),mb_strlen($_4ByteString,"UTF-32"),"UTF-32");		
			}
			else
			{
				//decodedCharacter is null, so add the single, unencoded character to the decodedString and remove the 1st character from start of input string
				
				// character did not match an entity or numeric encoding in context of trailing characters, so use the character
				$decodedString .= mb_convert_encoding(mb_substr($_4ByteString,0,1,"UTF-32"),$initialEncoding,"UTF-32");
				
				// eat the single, unencoded character portion off the start of the UTF-32 converted input string
				$_4ByteString = mb_substr($_4ByteString,1,mb_strlen($_4ByteString,"UTF-32"),"UTF-32");
			}
		}
		
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
			
			if ( $ordinalValue > 255 )
			{
				return null;
			}
			return self::$hex[$ordinalValue];
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
	        //detect encoding, special-handling for chr(172) and chr(128) to chr(159) which fail to be detected by mb_detect_encoding()
  	  		if((ord($string) == 172)  || (ord($string) >= 128 && ord($string) <= 159))
  	  		{
  	  			$detectedEncoding = "ASCII";	//although these chars are beyond ASCII range, if encoding is forced to ISO-8859-1 they will all encode to &#x31;
  	  		}
  	      	else if(ord($string) >= 160 && ord($string) <= 255)
  	  		{
  	  			$detectedEncoding = "ISO-8859-1";
  	  		}
  	  		else
  	  		{
  	  			$detectedEncoding = mb_detect_encoding($string);
  	  		}
  	  		
  	  		return $detectedEncoding;
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
}