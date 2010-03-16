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
        if(substr(PHP_OS, 0, 3) == 'WIN') {
        	return;
        }
        
        $this->fail();
        
        //TODO: convert JAVA tests to PHP
        /*
        // make sure we have what /bin/sh is pointing at in the allowed exes for the test
                // and a usable working dir
                File binSh = new File("/bin/sh").getCanonicalFile();
                ESAPI.setSecurityConfiguration(
                        new Conf(
                                ESAPI.securityConfiguration(),
                                Collections.singletonList(binSh.getPath()),
                                new File("/tmp")
                        )
                );

                Executor instance = ESAPI.executor();
                File executable = binSh;
                List params = new ArrayList();
                try {
                        params.add("-c");
                        params.add("ls");
                        params.add("/");
                        String result = instance.executeSystemCommand(executable, new ArrayList(params) );
                        System.out.println( "RESULT: " + result );
                        assertTrue(result.length() > 0);
                } catch (Exception e) {
                        fail(e.getMessage());
                }
                try {
                        File exec2 = new File( executable.getPath() + ";./inject" );
                        String result = instance.executeSystemCommand(exec2, new ArrayList(params) );
                        System.out.println( "RESULT: " + result );
                        fail();
                } catch (Exception e) {
                        // expected
                }
                try {
                        File exec2 = new File( executable.getPath() + "/../bin/sh" );
                        String result = instance.executeSystemCommand(exec2, new ArrayList(params) );
                        System.out.println( "RESULT: " + result );
                        fail();
                } catch (Exception e) {
                        // expected
                }
                try {
                        params.add(";ls");
                        String result = instance.executeSystemCommand(executable, new ArrayList(params) );
                        System.out.println( "RESULT: " + result );
                } catch (Exception e) {
                        fail();
                }
        */
    }
}
?>