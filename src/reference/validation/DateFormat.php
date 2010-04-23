<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project.
 *
 * LICENSE: This source file is subject to the New BSD license.  You should read
 * and accept the LICENSE before you use, modify, and/or redistribute this
 * software.
 * 
 * PHP version 5.2
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    Johannes B. Ullrich <jullrich@sans.edu>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   SVN: $Id$
 * @link      http://www.owasp.org/index.php/ESAPI
 */

/**
 * Helper class.
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    Johannes B. Ullrich <jullrich@sans.edu>
 * @author    jah <jah@jahboite.co.uk>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   Release: @package_version@
 * @link      http://www.owasp.org/index.php/ESAPI
 */
class DateFormat {
	private $format = array();
	const types = array('SMALL','MEDIUM','LONG','FULL');
	
	function __construct($format=null, $type='MEDIUM') {
		$this->setformat($format,$type);
	}
	
	function setformat($format, $type='MEDIUM') {
		
		if ( is_array($format)) {
			foreach ( self::types as $t ) {
				if ( key_exists($t, $format)) {
					$this->format[$t] = $format[$t];		
				}
			}
		} else {
			if ( in_array($type, self::types) ) {
				$this->format[$type] = $format;
			} else {
				throw ValidationException("invalid date type " . $type);
			}						
		}
	}
}

?>