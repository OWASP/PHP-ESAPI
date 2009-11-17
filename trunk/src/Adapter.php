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
 * @author Mike Boberski (mike.boberski @ owasp.org)
 * @created 2009
 */

require_once dirname(__FILE__).'/errors/IntrusionException.php';
require_once dirname(__FILE__).'/errors/ValidationException.php';

interface Adapter {

	function getValidEmployeeID($eid);
	function isValidEmployeeID($eid);

}

