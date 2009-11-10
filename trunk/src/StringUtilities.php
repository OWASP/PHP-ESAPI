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
 * @package org.owasp.esapi
 */

/**
 * String utilities used in various filters.
 * 
 * @author Andrew van der Stock < vanderaj .(at). owasp.org >
 * @since 1.4
 */
class StringUtilities {

	/**
	 * Removes all unprintable characters from a string 
	 * and replaces with a space for use in an HTTP header
	 * @param input
	 * @return the stripped header
	 */
	public static function stripControls( $input ) {
		if (empty($input)) {
			return '';
		}

		$i = str_split($input);
		
		$sb = '';
		foreach ( $i as $c )
		{
			if ( $c > chr(32) && $c < chr(127) ) {
				$sb .= $c;
			} else {
				$sb .= ' ';
			}
		}
		
		return $sb;
	}

	
    /**
     * Union two character arrays.
     * 
     * @param c1 the c1
     * @param c2 the c2
     * @return the char[]
     */
    public static function union($c1, $c2) {
    	if (empty($c1) && empty($c2))
    	{
    		return null;
    	}
    	
		return sort(array_unique(array_merge($c1, $c2)));
    }


	/**
     * Returns true if the character is contained in the provided StringBuffer.
     */
    public static function contains($haystack, $c) {
    	if ( empty($haystack) || empty($c) ) {
    		return false;
    	}
    	
    	return ( strpos($haystack, $c) !== false ) ? true : false;
    }
}