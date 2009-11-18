<?php
/**
 * Sample code that calls the reference implementation of the Adapter interface. 
 * 
 * Copyright (c) 2007 - 2009 The OWASP Foundation
 *
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 * 
 * @author <a href="mailto:boberski_michael@bah.com?subject=ESAPI for PHP question">Mike Boberski</a> at <a href="http://www.bah.com">Booz Allen Hamilton</a>
 * @since Version 1.0
 */

require_once dirname(__FILE__).'/../src/ESAPI.php';

$ESAPI = new ESAPI(dirname(__FILE__)."/../ESAPI.xml");
$ESAPI = new ESAPI();

$adapter = ESAPI::getAdapter();

echo "Testing input validation for an employee ID...";

echo "\n\nTest # 1: Expected result: Successs.";
if($adapter->isValidEmployeeID("123456")){
	echo "\nInput employee ID successfully validated!";
}else{
	echo "\nInput employee ID validation failed!";	
}

echo "\n\nTest # 2: Expected result: Failure.";
if($adapter->isValidEmployeeID("1234567")){
	echo "\nInput employee ID successfully validated!\n\n";
}else{
	echo "\nInput employee ID validation failed!\n\n";	
}

?>