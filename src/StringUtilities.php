<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project.
 * 
 * PHP version 5.2
 *
 * LICENSE: This source file is subject to the New BSD license.  You should read
 * and accept the LICENSE before you use, modify, and/or redistribute this
 * software.
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    Jeff Williams <jeff.williams@aspectsecurity.com>
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   SVN: $Id$
 * @link      http://www.owasp.org/index.php/ESAPI
 */

/**
 * String utilities used in various filters.
 * 
 * PHP version 5.2
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    Jeff Williams <jeff.williams@aspectsecurity.com>
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   Release: @package_version@
 * @link      http://www.owasp.org/index.php/ESAPI
 */
class StringUtilities
{

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