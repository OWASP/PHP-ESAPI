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
    	// check for immune characters
		if ( $this->containsCharacter( $c, $immune ) ) {
			return $c;
		}
    	
		// check for alphanumeric characters
		$hex = $this->getHexForNonAlphanumeric( $c );
		if(is_null($hex)) {
			return $c;
		}
		
		return "^".$c;
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