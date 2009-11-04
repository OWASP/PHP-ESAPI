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


require_once ('Codec.php');

/**
 * Implementation of the Codec interface for '^' encoding from Windows command shell.
 * 
 * @author 
 * @since 1.4
 * @see org.owasp.esapi.Encoder
 */
 
class WindowsCodec extends Codec
{

    /**
     * Public Constructor 
     */
    function __construct()
    {
  		parent::__construct();
    }

 
    /**
     * {@inheritDoc}
     */
    public function encodeCharacter($immune,$c)
    {
  
        //detect encoding, special-handling for chr(172) and chr(128) to chr(159) which fail to be detected by mb_detect_encoding()
  		if((ord($c) == 172)  || (ord($c) >= 128 && ord($c) <= 159))
  		{
  			$initialEncoding = "ASCII";
  		}
      	else if(ord($c) >= 160 && ord($c) <= 255)
  		{
  			$initialEncoding = "ISO-8859-1";
  		}
  		else
  		{
  			$initialEncoding = mb_detect_encoding($c);
  		}
  		
  		// Normalize encoding to UTF-32
  		$_4ByteUnencodedOutput = $this->normalizeEncoding($c);
  		
  		// Start with nothing; format it to match the encoding of the string passed as an argument.
  		$encodedOutput = mb_convert_encoding("", $initialEncoding);
  		
  		// Grab the 4 byte character.
  		$_4ByteCharacter = $this->forceToSingleCharacter($_4ByteUnencodedOutput);
  		
  		// Get the ordinal value of the character.
  		list(, $ordinalValue) = unpack("N", $_4ByteCharacter);
  		
  		// Check for immune characters.
  		foreach($immune as $immuneCharacter)
  		{
  			// Convert to UTF-32 (4 byte characters, regardless of actual number of bytes in the character).
  			$_4ByteImmuneCharacter = $this->normalizeEncoding($immuneCharacter);
  			
  			// Ensure it's a single 4 byte character (since $immune is an array of strings) by grabbing only the 1st multi-byte character.
  			$_4ByteImmuneCharacter = $this->forceToSingleCharacter($_4ByteImmuneCharacter);
  			
  			// If the character is immune then return it.
  			if($_4ByteCharacter === $_4ByteImmuneCharacter)
  			{
  				return $encodedOutput.chr($ordinalValue);
  			}
  		}
  		
  		// Check for alphanumeric characters
  		$hex = $this->getHexForNonAlphanumeric($_4ByteCharacter);
  		if($hex===null)
  		{
  			return $encodedOutput.chr($ordinalValue);
  		}
  		
  		$encodedOutput .= "^".$_4ByteCharacter;
  		
  		// Encoded!
  		return $encodedOutput;
    
    }

 
    
    /**
     * {@inheritDoc}
     */
    public function decodeCharacter($input)
    {
    	$first = mb_substr($input, 0, 1);
    	if(is_null($first)) {
			return null;
		}
    			
		// if this is not an encoded character, return null
		if ( $first != '^' ) {
			return null;
		}
		
    	$second = mb_substr($input, 1, 1);
    	return $second;
    }
}