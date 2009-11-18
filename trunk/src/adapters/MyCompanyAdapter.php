<?php

/**
 * Reference implementation of the Adapter interface. This implementation calls
 * relies on the DefaultValidator and DefaultSecurityConfiguration ESAPI for PHP Core 
 * security controls.
 * 
 * Copyright (c) 2007 - 2009 The OWASP Foundation
 *
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 * 
 * @author <a href="mailto:boberski_michael@bah.com?subject=ESAPI for PHP question">Mike Boberski</a> at <a href="http://www.bah.com">Booz Allen Hamilton</a>
 * @since Version 1.0
 */

require_once dirname ( __FILE__ ) . '/../ESAPI.php';
require_once dirname ( __FILE__ ) . '/../Adapter.php';

class MyCompanyAdapter implements Adapter {
	
	/**
	 * @inheritdoc
	 */
	function getValidEmployeeID($eid) {
		$val = ESAPI::getValidator();
		$val->getValidInput(			
			"My Company Employee ID", 	//context
			$eid, 						//input
			"EmployeeID", 				//type
			6, 							//max length
			false						//allow null
			);
	}
	
	/**
	 * @inheritdoc
	 */
	function isValidEmployeeID($eid) {
		try {
			$this->getValidEmployeeID($eid);
			return true;
		} catch ( Exception $e ) {
			return false;
		}
		
	}
	
}
?>