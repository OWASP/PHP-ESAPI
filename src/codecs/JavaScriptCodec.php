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
 * @author Mike Boberski
 * @created 2009
 * @since 1.6
 * @package org.owasp.esapi.codecs
 */


require_once ('Codec.php');

/**
 * Implementation of the Codec interface for backslash encoding in JavaScript.
 * 
 * @author 
 * @since 1.4
 * @see org.owasp.esapi.Encoder
 */
class JavaScriptCodec extends Codec
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
	 * 
	 * Returns backslash encoded numeric format. Does not use backslash character escapes
	 * such as, \" or \' as these may cause parsing problems. For example, if a javascript
	 * attribute, such as onmouseover, contains a \" that will close the entire attribute and
	 * allow an attacker to inject another script attribute.
     *
     * @param immune
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
		   return $encodedOutput.chr($ordinalValue);
		}
    	
    	// Check for alphanumeric characters
  		$hex = $this->getHexForNonAlphanumeric($_4ByteCharacter);
  		if($hex===null)
  		{
  			return $encodedOutput.chr($ordinalValue);
  		}
  		
  		// Do not use these shortcuts as they can be used to break out of a context
		// if ( ch == 0x00 ) return "\\0";
		// if ( ch == 0x08 ) return "\\b";
		// if ( ch == 0x09 ) return "\\t";
		// if ( ch == 0x0a ) return "\\n";
		// if ( ch == 0x0b ) return "\\v";
		// if ( ch == 0x0c ) return "\\f";
		// if ( ch == 0x0d ) return "\\r";
		// if ( ch == 0x22 ) return "\\\"";
		// if ( ch == 0x27 ) return "\\'";
		// if ( ch == 0x5c ) return "\\\\";
    	
		// encode up to 256 with \\xHH
		$pad = mb_substr("00", mb_strlen($hex));
		if($ordinalValue < 256)
      	{
      		return "\\x".$pad.strtoupper($hex);
     	}
     	
     	// otherwise encode with \\uHHHH
     	$pad = mb_substr("0000", mb_strlen($hex));
      	return "\\u".$pad.strtoupper($hex);
 
    }

	/**
	 * {@inheritDoc}
	 * 
	 * Returns the decoded version of the character starting at index, or
	 * null if no decoding is possible.
	 * See http://www.planetpdf.com/codecuts/pdfs/tutorial/jsspec.pdf 
	 * Formats all are legal both upper/lower case:
	 *   \\a - special characters
	 *   \\xHH
	 *   \\uHHHH
	 *   \\OOO (1, 2, or 3 digits)
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
     	
    	// \0 collides with the octal decoder and is non-standard
		// if ( second.charValue() == '0' ) {
		//	return Character.valueOf( (char)0x00 );
		if($second == $this->normalizeEncoding('b'))
			return chr(hexdec("8"));
 		else if($second == $this->normalizeEncoding('t'))
			return chr(hexdec("9"));
 		else if($second == $this->normalizeEncoding('n'))
			return chr(hexdec("a"));
 		else if($second == $this->normalizeEncoding('v'))
			return chr(hexdec("b"));
 		else if($second == $this->normalizeEncoding('f'))
			return chr(hexdec("c"));
 		else if($second == $this->normalizeEncoding('r'))
			return chr(hexdec("d"));
 		else if($second == $this->normalizeEncoding('\"'))
			return chr(hexdec("22"));
 		else if($second == $this->normalizeEncoding('\''))
			return chr(hexdec("27"));
 		else if($second == $this->normalizeEncoding('\\'))
			return chr(hexdec("5c"));
			
		// look for \\xXX format
 		else if(strtolower($second) == $this->normalizeEncoding('x')){
	   		// check for exactly two hex digits following
	   		$potentialHexString = $this->normalizeEncoding('');
	   		for($i=0; $i<2; $i++)
	   		{
	   			$c = mb_substr($input,2+$i,1,"UTF-32");
	   			if($c!=null) $potentialHexString .= $c;
	   		}
	   		if(mb_strlen($potentialHexString,"UTF-32") == 2)
	   		{
	   			$charFromHex = $this->normalizeEncoding($this->parseHex($potentialHexString));
	   			return array('decodedCharacter'=>$charFromHex,'encodedString'=>mb_substr($input,0,4,"UTF-32"));
	   		}
	   		return array('decodedCharacter'=>null,'encodedString'=>null);
 		}
 		
 		// look for \\uXXXX format
 		else if(strtolower($second) == $this->normalizeEncoding('u')){
 			// Search for exactly 4 hex digits following
	   		$potentialHexString = $this->normalizeEncoding('');
	   		for($i=0; $i<4; $i++)
	   		{
	   			$c = mb_substr($input,1+$i,1,"UTF-32");
	   			if($c!=null) $potentialHexString .= $c;
	   		}
	   		if(mb_strlen($potentialHexString,"UTF-32") == 4)
	   		{
	   			$charFromHex = $this->normalizeEncoding($this->parseHex($potentialHexString));
	   			return array('decodedCharacter'=>$charFromHex,'encodedString'=>mb_substr($input,0,3,"UTF-32"));
	   		}
	   		return array('decodedCharacter'=>null,'encodedString'=>null);
  		}
 		
  		// look for one, two, or three octal digits
		else if(preg_match('/[0-7]+/', $second)>0){
			// get digit 1
			$digit1 = $second;
			$digits = $digit1;
			// get digit 2 if present
    		$digit2 = mb_substr($_4ByteEncodedInput,2,1,"UTF-32");
			if(!preg_match('/[0-7]+/', $digit2)){
				$digit2 = "";
			} else{
				$digits .= $digit2;
				// get digit 3 if present
				$digit3 = mb_substr($_4ByteEncodedInput,3,1,"UTF-32");
				if(!preg_match('/[0-7]+/', $digit3)){
					$digit3 = "";
				} else{
					$digits = $digit1.$digit2.$digit3;
				}
			}
			return chr(octdec($digits));
		}	
			
  		// ignore the backslash and return the character
		return $second;
		
    }
    
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
