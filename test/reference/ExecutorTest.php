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
 * @author Andrew van der Stock (vanderaj @ owasp.org)
 * @created 2009
 */
require_once dirname(__FILE__).'/../../src/ESAPI.php';
require_once dirname(__FILE__).'/../../src/reference/DefaultExecutor.php';

class ExecutorTest extends UnitTestCase 
{
	function setUp() 
	{
		global $ESAPI;
		
		if ( !isset($ESAPI)) 
		{
			$ESAPI = new ESAPI();
		}
	}
		
	function tearDown()
	{
		
	}
		
	/**
	 * Test of executeSystemCommand method, of Executor
	*/
	function testExecuteWindowsSystemCommand() 
    {   	
        if(substr(PHP_OS, 0, 3) != 'WIN') {
        	return; // Not windows, not going to execute this path
        }
        
        try {
	    	$instance =  new DefaultExecutor();
	    	$params = array("/C", "dir");
	    	$result = $instance->executeSystemCommand("%SYSTEMROOT%\\System32\\cmd.exe", $params);
	    	$result = ESAPI::getEncoder()->encodeForHTML($result);
//	    	echo "<br />RESULT: $result<br />\n";
	    	$this->assertNotNull($result);
        }
    	catch ( Exception $e ) 
        {
        	$this->fail();
        }
        
        //TODO: add more tests
 
    	
    }
    
	/**
	 * Test of executeSystemCommand method, of Executor
	*/
    function testExecuteUnixSystemCommand()
    {
        if(substr(PHP_OS, 0, 3) == 'WIN') {
        	return;
        }
        
        //TODO: add tests
        	
        $this->fail();
    }	
}
?>