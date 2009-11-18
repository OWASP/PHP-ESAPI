<?php
/**
 * The Adapter interface defines a set of functions as part of an extended factory pattern 
 * implementation consisting of a new ESAPI security control interface and corresponding 
 * implementation, which in turn calls ESAPI security control reference implementations 
 * and/or security control reference implementations that were replaced with your own 
 * implementations. The ESAPI locator class would be called in order to retrieve a singleton 
 * instance of your new security control, which in turn would call ESAPI security control 
 * reference implementations and/or security control reference implementations that were 
 * replaced with your own implementations.
 * 
 * Copyright (c) 2007 - 2009 The OWASP Foundation
 *
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 * 
 * @author <a href="mailto:boberski_michael@bah.com?subject=ESAPI for PHP question">Mike Boberski</a> at <a href="http://www.bah.com">Booz Allen Hamilton</a>
 * @since Version 1.0
 */

require_once dirname(__FILE__).'/errors/IntrusionException.php';
require_once dirname(__FILE__).'/errors/ValidationException.php';

interface Adapter {

	/**
	 * Returns a valid employee ID as a string.
	 * @param $eid Employee ID to validate.
	 * @return A valid employee ID as a string.
	 * 
	 */
	function getValidEmployeeID($eid);
	
	/**
	 * Calls getValidEmployeeID and returns true if no exceptions are thrown.
	 * @param $eid Employee ID to validate.
	 * @return true, if employee ID is valid.
	 */
	function isValidEmployeeID($eid);

}

