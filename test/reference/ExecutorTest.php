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
 * @author  Andrew van der Stock <vanderaj @ owasp.org>
 * @author  Linden Darling <linden.darling@jds.net.au> 
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
    	if(substr(PHP_OS, 0, 3) != 'WIN')
        {
        	echo "testExecuteWindowsSystemCommand - on non-Windows platform, exiting<br />\n";
        	return; // Not windows, not going to execute this path
        }
        
        $instance =  new DefaultExecutor();
        $codec = new WindowsCodec();
        
        try
        {
        	$executable = '%SYSTEMROOT%\\System32\\cmd.exe';
	    	$params = array("/C", "dir");
	    	$result = $instance->executeSystemCommand($executable, $params);
	    	$result = ESAPI::getEncoder()->encodeForHTML($result);
	    	echo "RESULT: $result<br /><br />\n";
	    	$this->assertNotNull($result);
        }
    	catch ( Exception $e ) 
        {
        	$this->fail();
        }
        
        try
        {
        	$executable = '%SYSTEMROOT%\\System32\\;notepad.exe';
        	$params = array("/C", "dir");
        	$result= $instance->executeSystemCommand($executable, $params);
	    	$result = ESAPI::getEncoder()->encodeForHTML($result);
	    	echo "RESULT: $result<br /><br />\n";
	    	$this->assertNull($result);
        }
        catch( Exception $e )
        {
        	//expected
        	$this->pass();
        }
        
        try
        {
        	$executable = '%SYSTEMROOT%\\System32\\..\\cmd.exe';
        	$params = array("/C", "dir");
        	$result= $instance->executeSystemCommand($executable, $params);
	    	$result = ESAPI::getEncoder()->encodeForHTML($result);
	    	echo "RESULT: $result<br /><br />\n";
	    	$this->assertNull($result);
        }
        catch( Exception $e )
        {
        	//expected
        	$this->pass();
        }
        
    	try
        {
        	$executable = '%SYSTEMROOT%\\System32\\cmd.exe';
        	$params = array("/C", "dir");
        	$workdir = 'C:\\ridiculous';
        	$result = $instance->executeSystemCommand_effectLonghand($executable, $params, $workdir, $codec, false);
	    	$result = ESAPI::getEncoder()->encodeForHTML($result);
	    	echo "RESULT: $result<br /><br />\n";
	    	$this->assertNull($result);
        }
        catch( Exception $e )
        {
        	//expected
        }
        
    	try
        {
        	$params = array("/C", "dir", "&dir");
        	$result= $instance->executeSystemCommand($executable, $params);
	    	$result = ESAPI::getEncoder()->encodeForHTML($result);
	    	echo "RESULT: $result<br /><br />\n";
	    	$this->assertNotNull($result);
        }
        catch( Exception $e )
        {
        	$this->fail();
        }
        
    	try
        {
        	$params = array("/C", "dir", "c:\\autoexec.bat");
        	$result= $instance->executeSystemCommand($executable, $params);
	    	$result = ESAPI::getEncoder()->encodeForHTML($result);
	    	echo "RESULT: $result<br /><br />\n";
	    	$this->assertNotNull($result);
        }
        catch( Exception $e )
        {
        	$this->fail();
        }
        
    	try
        {
        	$params = array("/C", "dir", "c:\\autoexec.bat c:\\config.sys");
        	$result= $instance->executeSystemCommand($executable, $params);
	    	$result = ESAPI::getEncoder()->encodeForHTML($result);
	    	echo "RESULT: $result<br /><br />\n";
	    	$this->assertNotNull($result);
        }
        catch( Exception $e )
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
        if(substr(PHP_OS, 0, 3) == 'WIN')
        {
        	echo "testExecuteUnixSystemCommand - on Windows platform, exiting<br />\n";
        	return;		// Is windows, not going to execute this path
        }
        
        $instance =  new DefaultExecutor();
        
    	try
        {
        	$executable = '/bin/sh';
	    	$params = array("-c", "ls", "/");
	    	$result = $instance->executeSystemCommand($executable, $params);
	    	$result = ESAPI::getEncoder()->encodeForHTML($result);
	    	echo "RESULT: $result<br /><br />\n";
	    	$this->assertNotNull($result);
        }
    	catch ( Exception $e ) 
        {
        	$this->fail();
        }
        
    	try
        {
        	$executable = '/bin/sh;./inject';
	    	$params = array("-c", "ls", "/");
	    	$result = $instance->executeSystemCommand($executable, $params);
	    	$result = ESAPI::getEncoder()->encodeForHTML($result);
	    	echo "RESULT: $result<br /><br />\n";
	    	$this->assertNull($result);
        }
    	catch ( Exception $e ) 
        {
        	//expected
        }
        
    	try
        {
        	$executable = '/bin/sh/../bin/sh';
	    	$params = array("-c", "ls", "/");
	    	$result = $instance->executeSystemCommand($executable, $params);
	    	$result = ESAPI::getEncoder()->encodeForHTML($result);
	    	echo "RESULT: $result<br /><br />\n";
	    	$this->assertNull($result);
        }
    	catch ( Exception $e ) 
        {
        	//expected
        }
        
    	try
        {
        	$executable = '/bin/sh';
	    	$params = array("-c", "ls", "/", ";ls");
	    	$result = $instance->executeSystemCommand($executable, $params);
	    	$result = ESAPI::getEncoder()->encodeForHTML($result);
	    	echo "RESULT: $result<br /><br />\n";
	    	$this->assertNotNull($result);
        }
    	catch ( Exception $e ) 
        {
        	$this->fail();
        }
    }
}
?>