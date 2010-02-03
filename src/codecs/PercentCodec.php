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
 * @author Mike Boberski
 * @created 2009 
 * @since 1.6
 */


require_once ('Codec.php');

/**
 * Implementation of the Codec interface for percent encoding (aka URL encoding).
 * 
 * @author 
 * @since 1.4
 * @see org.owasp.esapi.Encoder
 */
class PercentCodec extends Codec
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
    public function encodeCharacter($immune, $c)
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
      if ( $this->containsCharacter( $_4ByteCharacter, $immune ) )
      {
      	// character is immune, therefore return character...
        return $encodedOutput.chr($ordinalValue);
      }

      // check for alphanumeric characters
      $hex = $this->getHexForNonAlphanumeric( $_4ByteCharacter );
      if($hex===null)
      {
      	//character is alphanumric, therefore return the character...
        return $encodedOutput.chr($ordinalValue);
      }
      
      if($ordinalValue < 16)
      {
      	// ordinalValue is less than 16, therefore prepend hex with a 0...
        $hex = "0".strtoupper($hex);
      }
      
      return "%".strtoupper($hex);
    }
  
    /**
     * {@inheritDoc}
     */
    public function decodeCharacter($input)
   {
    	if(mb_substr($input,0,1,"UTF-32") === null)
    	{
    		// 1st character is null, so return null
    		// eat the 1st character off the string and return null
    		$input = mb_substr($input,1,mb_strlen($input,"UTF-32"),"UTF-32"); //this is not neccessary
    		return array('decodedCharacter'=>null,'encodedString'=>null);
    	}
    	
    	// if this is not an encoded character, return null
    	if(mb_substr($input,0,1,"UTF-32") != $this->normalizeEncoding('%'))
    	{
    		// 1st character is not part of encoding pattern, so return null
    		return array('decodedCharacter'=>null,'encodedString'=>null);
    	}
    	
    	// 1st character is part of encoding pattern...
    	
   		// check for exactly two hex digits following
   		$potentialHexString = $this->normalizeEncoding('');
   		$limit = min(2, mb_strlen($input, "UTF-32") - 1);
   		for($i=0; $i<$limit; $i++)
   		{
   			$c = mb_substr($input, 1+$i, 1, "UTF-32");
   			if ($c != '') {
   			    $ph = $this->parseHex($c);
   			    if ($ph !== null) {
   			        $potentialHexString .= $c;
   			    }
   			}
   		}
   		if(mb_strlen($potentialHexString,"UTF-32") == 2)
   		{
   			$charFromHex = $this->normalizeEncoding($this->parseHex($potentialHexString));
   			return array('decodedCharacter'=>$charFromHex,'encodedString'=>mb_substr($input,0,3,"UTF-32"));
   		}
   		return array('decodedCharacter'=>null,'encodedString'=>null);
    }
    
	  /**
     * Parse a hex encoded entity
     * 
     * @param input
     * 							Hex encoded input (such as 437ae;)
     * 
     * @return
     * 							Returns an array containing two objects:
     * 							'decodedCharacter' => null if input is null, the character of input after decoding
     * 							'encodedString' => the string that was decoded or found to be malformed
     */
    private function parseHex($input)
    {
    	$hexString = mb_convert_encoding("", mb_detect_encoding($input));	//encoding should be UTF-32, so why detect it?
    	$inputLength = mb_strlen($input,"UTF-32");
    	for($i=0; $i<$inputLength; $i++)
    	{
    		// Get the ordinal value of the character.
			  list(, $ordinalValue) = unpack("N", mb_substr($input,$i,1,"UTF-32"));
			
			  // if character is a hex digit, add it and keep on going
    		if(preg_match("/^[0-9a-fA-F]/",chr($ordinalValue)))
    		{
    			// hex digit found, add it and continue...
    			$hexString .= mb_substr($input,$i,1,"UTF-32");
    		}
    		// if character is a semicolon, then eat it and quit
    		else if(mb_substr($input,$i,1,"UTF-32") == $this->normalizeEncoding(';'))
    		{
    			$trailingSemicolon = $this->normalizeEncoding(';');  //this parameter is not utilised by this method, consider removing
    			break;
    		}
    		// otherwise just quit
    		else
    		{
    			break;
    		}
		  }
    	try
    	{
    		// trying to convert hexString to integer...
    		if ($hexString == mb_convert_encoding("", mb_detect_encoding($input))) return null;
    		$parsedInteger = (int)hexdec($hexString);
    		$parsedCharacter = chr($parsedInteger);
    		return $parsedCharacter;
    	}
    	catch(Exception $e)
    	{
    		//TODO: throw an exception for malformed entity?
    		return null;
    	}
    }
}