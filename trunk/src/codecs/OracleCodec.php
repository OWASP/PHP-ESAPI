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
 * @since 1.4
 * @package ESAPI_Codecs
 */


require_once ('Codec.php');

/**
 * Implementation of the Codec interface for Oracle strings. See http://download-uk.oracle.com/docs/cd/B10501_01/text.920/a96518/cqspcl.htm
 * for more information.
 * 
 * @see <a href="http://download-uk.oracle.com/docs/cd/B10501_01/text.920/a96518/cqspcl.htm">Special Characters in Oracle Queries</a>
 * 
 * @author 
 * @since 1.4
 * @see org.owasp.esapi.Encoder
 */
class OracleCodec extends Codec
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
  		
  		//check if character is an apostrophe
  		if($_4ByteCharacter == $this->normalizeEncoding('\''))
  		{
  			return $encodedOutput.'\'\'';
  		}
  		
  		return $encodedOutput.chr($ordinalValue);
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
    	if(mb_substr($input,0,1,"UTF-32") != $this->normalizeEncoding("'"))
    	{
    		// 1st character is not part of encoding pattern, so return null
    		return array('decodedCharacter'=>null,'encodedString'=>null);
    	}
    	
    	// 1st character is part of encoding pattern...
    	
    	// if this is not an encoded character, return null
    	if(mb_substr($input,1,1,"UTF-32") != $this->normalizeEncoding("'"))
    	{
    		// 1st character is not part of encoding pattern, so return null
    		return array('decodedCharacter'=>null,'encodedString'=>null);
    	}
    	
    	return array('decodedCharacter'=>$this->normalizeEncoding("'"),'encodedString'=>$this->normalizeEncoding("''"));
    }
}