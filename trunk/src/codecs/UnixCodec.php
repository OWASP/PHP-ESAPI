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


require_once ('Codec.php');

/**
 * Implementation of the Codec interface for '\' encoding from Unix command shell.
 * 
 * @author 
 * @since 1.4
 * @see org.owasp.esapi.Encoder
 */
class UnixCodec extends Codec
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
  		$initialEncoding = $this->detectEncoding($c);

    	// Normalize encoding to UTF-32
  		$_4ByteUnencodedOutput = $this->normalizeEncoding($c);
  		
  		// Start with nothing; format it to match the encoding of the string passed as an argument.
  		$encodedOutput = mb_convert_encoding("", $initialEncoding);
    
  		// Grab the 4 byte character.
  		$_4ByteCharacter = $this->forceToSingleCharacter($_4ByteUnencodedOutput);
  		
  		// Get the ordinal value of the character.
  		list(, $ordinalValue) = unpack("N", $_4ByteCharacter);
  		
      // check for immune characters
    	if( $this->containsCharacter( $_4ByteCharacter, $immune ) )
    	{
			   return $encodedOutput.chr($ordinalValue);
		  }
    	
    	// Check for alphanumeric characters
  		$hex = $this->getHexForNonAlphanumeric($_4ByteCharacter);
  		if($hex===null)
  		{
  			return $encodedOutput.chr($ordinalValue);
  		}
		
		  return $encodedOutput."\\".$c;
    }

 
    /**
     * {@inheritDoc}
     */
    public function decodeCharacter($input)
    {
    	// Assumption/prerequisite: $c is a UTF-32 encoded string
		  $_4ByteEncodedInput = $input;
    
    	if(mb_substr($_4ByteEncodedInput,0,1,"UTF-32") === null)
    	{
    		// 1st character is null, so return null
    		// eat the 1st character off the string and return null
    		$_4ByteEncodedInput = mb_substr($input,1,mb_strlen($_4ByteEncodedInput,"UTF-32"),"UTF-32");	//no point in doing this
    		return array('decodedCharacter'=>null,'encodedString'=>null);
    	}
    	
    	// if this is not an encoded character, return null
    	if(mb_substr($_4ByteEncodedInput,0,1,"UTF-32") != $this->normalizeEncoding('\\'))
    	{
    		// 1st character is not part of encoding pattern, so return null
    		
    		return array('decodedCharacter'=>null,'encodedString'=>null);
    	}
    	
    	// 1st character is part of encoding pattern...
    	
    	$second = mb_substr($_4ByteEncodedInput,1,1,"UTF-32");
    	
    	return array('decodedCharacter'=>$second,'encodedString'=>mb_substr($input,0,2,"UTF-32"));
    }
}