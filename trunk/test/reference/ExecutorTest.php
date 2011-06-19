<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 * 
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * <a href="http://www.owasp.org/index.php/ESAPI">http://www.owasp.org/index.php/ESAPI</a>.
 *
 * Copyright (c) 2007 - 2010 The OWASP Foundation
 * 
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 * 
 * @author  Andrew van der Stock <vanderaj @ owasp.org>
 * @author  Linden Darling <linden.darling@jds.net.au> 
 * @created 2009
 */
require_once dirname(__FILE__).'/../../src/ESAPI.php';
require_once dirname(__FILE__).'/../../src/errors/ExecutorException.php';
require_once dirname(__FILE__).'/../../src/reference/DefaultExecutor.php';

class ExecutorTest extends PHPUnit_Framework_TestCase 
{
	private $_os;
	private $_instance;
	
	private $_executable;
	private $_params;
	private $_workdir;
	
	const PLATFORM_WINDOWS 	= 1;
	const PLATFORM_UNIX 	= 2;
	 
	function setUp() 
	{
		global $ESAPI;
		
		if ( !isset($ESAPI)) 
		{
			$ESAPI = new ESAPI();
		}
		
	    if (substr(PHP_OS, 0, 3) == 'WIN')
        {
        	$this->_os = self::PLATFORM_WINDOWS; 
			$this->_executable = '%SYSTEMROOT%\\system32\\cmd.exe';
	    	$this->_params = array("/C", "dir");
	    	$this->_workdir = '%SYSTEMROOT%\\Temp';
        }
        else 
        {
        	$this->_os = self::PLATFORM_UNIX;
        	$this->_executable = '/bin/sh';
	    	$this->_params = array("-c", "'ls /'");
	    	$this->_workdir = '/tmp';
        }
        
        $this->_instance = new DefaultExecutor();
	}
		
	function tearDown()
	{
		
	}
		
	/**
	 * Test of executeSystemCommand method, of Executor
	 */
	function testExecuteWindowsLegalSystemCommand() 
    {   	
    	if ( $this->_os != self::PLATFORM_WINDOWS )
        {
        	return;
        }
        
        try
        {
	    	$result = $this->_instance->executeSystemCommand($this->_executable, $this->_params);
	    	$this->assertNotNull($result);
        }
    	catch ( ExecutorException $e ) 
        {
        	$this->fail($e->getMessage());
        }
    }

    /**
	 * Test to ensure that bad commands fail
	 */
	function testExecuteWindowsInjectIllegalSystemCommand() 
    {   	
    	if ( $this->_os != self::PLATFORM_WINDOWS )
        {
        	return;
        }
        
        $this->setExpectedException('ExecutorException');
        
		$this->_executable = '%SYSTEMROOT%\\System32\\;notepad.exe';
		$result = $this->_instance->executeSystemCommand($this->_executable, $this->_params);
		$this->fail('Should not execute non-canonicalized path');
    }
    
    /**
	 * Test of file system canonicalization
	 */
	function testExecuteWindowsCanonicalization() 
    {   	
    	if ( $this->_os != self::PLATFORM_WINDOWS )
        {
        	return;
        }

        $this->setExpectedException('ExecutorException');
        
      	$this->_executable = '%SYSTEMROOT%\\System32\\..\\cmd.exe';
      	$result = $this->_instance->executeSystemCommand($this->_executable, $this->_params);
	   	$this->fail('Should not execute non-canonicalized path');
    }
    
    /**
     *	Test to see if a good work directory is properly handled. 
     */
    function testExecuteWindowsGoodWorkDirectory() 
    {   	
    	if ( $this->_os != self::PLATFORM_WINDOWS )
        {
        	return;
        }
        
    	try
        {
        	$result = $this->_instance->executeSystemCommandLonghand($this->_executable, $this->_params, $this->_workdir, false);
	    	$this->assertNotNull($result);
        }
        catch( ExecutorException $e )
        {
        	$this->fail($e->getMessage());
        }
    }
    
    
    /**
     *	Test to see if a non-existent work directory is properly handled. 
     */
    function testExecuteWindowsBadWorkDirectory() 
    {   	
    	if ( $this->_os != self::PLATFORM_WINDOWS )
        {
        	return;
        }
        
        $this->setExpectedException('ExecutorException');
        
       	$this->_workdir = 'C:\\ridiculous';
      	$result = $this->_instance->executeSystemCommandLonghand($this->_executable, $this->_params, $this->_workdir, false);
	   	$this->fail('Should not execute with a bad working directory');
    }
    
	/**
	 * Test to prevent chained command execution
	 */
    function testExecuteWindowsChainedCommand() 
    {   	
    	if ( $this->_os != self::PLATFORM_WINDOWS )
        {
        	return;
        }
        
        $this->setExpectedException('ExecutorException');
        
      	$this->_executable .= " & dir & rem ";
      	$result = $this->_instance->executeSystemCommand($this->_executable, $this->_params);
    	$this->fail("Executed chained command, output: ". $result);
    }
    
    /**
	 * Test to prevent chained command execution
	 */
    function testExecuteWindowsChainedParameter() 
    {   	
    	if ( $this->_os != self::PLATFORM_WINDOWS )
        {
        	return;
        }
        
    	try
        {
        	$this->_params[] = "&dir";
        	$result = $this->_instance->executeSystemCommand($this->_executable, $this->_params);
	    	$this->assertNotNull($result);
        }
        catch( ExecutorException $e )
        {
        	$this->fail($e->getMessage());
        }
    }
    
    /*
     *	Test to see if the escaping mechanism renders supplemental results safely 
     */
    function testExecuteWindowsDoubleArgs() 
    {   	
    	if ( $this->_os != self::PLATFORM_WINDOWS )
        {
        	return;
        }
                
    	try
        {
        	$this->_params[] = "c:\\autoexec.bat c:\\config.sys";
        	$result = $this->_instance->executeSystemCommand($this->_executable, $this->_params);
	    	$this->assertNotNull($result);
        }
        catch ( ExecutorException $e )
        {
        	$this->fail($e->getMessage());
        }
    }
    
	
	/**
	 * Test of executeSystemCommand method, of Executor
	 */
	function testExecuteUnixLegalSystemCommand() 
    {   	
    	if ( $this->_os != self::PLATFORM_UNIX )
        {
        	return;
        }

        try
        {
	    	$result = $this->_instance->executeSystemCommand($this->_executable, $this->_params);
	    	$this->assertNotNull($result);
        }
    	catch ( ExecutorException $e ) 
        {
        	$this->fail($e->getMessage());
        }
    }

    /**
	 * Test to ensure that bad commands fail
	 */
	function testExecuteUnixInjectIllegalSystemCommand() 
    {   	
    	if ( $this->_os != self::PLATFORM_UNIX )
        {
        	return;
        }
        
		$this->setExpectedException('ExecutorException');
		
	   	$this->_executable .= ';./inject';
      	$result = $this->_instance->executeSystemCommand($this->_executable, $this->_params);
	  	$this->fail('Should not have executed injected command');
    }
    
    /**
	 * Test of file system canonicalization
	 */
	function testExecuteUnixCanonicalization() 
    {   	
    	if ( $this->_os != self::PLATFORM_UNIX )
        {
        	return;
        }
        
        $this->setExpectedException('ExecutorException');
        
       	$this->_executable = '/bin/sh/../bin/sh';
      	$result = $this->_instance->executeSystemCommand($this->_executable, $this->_params);
    	$this->fail('Should not have executed uncanonicalized command');
    }
    
    /**
     *	Test to see if a good work directory is properly handled. 
     */
    function testExecuteUnixGoodWorkDirectory() 
    {   	
    	if ( $this->_os != self::PLATFORM_UNIX )
        {
        	return;
        }
        
    	try
        {
        	$result = $this->_instance->executeSystemCommandLonghand($this->_executable, $this->_params, $this->_workdir, false);
	    	$this->assertNotNull($result);
        }
        catch( ExecutorException $e )
        {
        	$this->fail($e->getMessage());
        }
    }
    
    
    /**
     *	Test to see if a non-existent work directory is properly handled. 
     */
    function testExecuteUnixBadWorkDirectory() 
    {   	
    	if ( $this->_os != self::PLATFORM_UNIX )
        {
        	return;
        }
        
    	$this->setExpectedException('ExecutorException');

    	$this->_workdir = '/ridiculous/';
        $result = $this->_instance->executeSystemCommandLonghand($this->_executable, $this->_params, $this->_workdir, false);
	    $this->fail('Bad working directory should not work.');
    }
    
	/**
	 * Test to prevent chained command execution
	 */
    function testExecuteUnixChainedCommand() 
    {   	
    	if ( $this->_os != self::PLATFORM_UNIX )
        {
        	return;
        }
        
		$this->setExpectedException('ExecutorException');
        
        $this->_executable .= " ; ls / ; # ";
        $result = $this->_instance->executeSystemCommand($this->_executable, $this->_params);
	    $this->fail("Executed chained command, output: ". $result);
    }
    
    /**
	 * Test to prevent chained command execution by adding a new command to end of the parameters
	 */
    function testExecuteUnixChainedParameter() 
    {   	
    	if ( $this->_os != self::PLATFORM_UNIX )
        {
        	return;
        }
            
    	try
        {
	    	$this->_params[] = ";ls";
        	$result = $this->_instance->executeSystemCommand($this->_executable, $this->_params);
	    	$this->assertNotNull($result);
        }
        catch( ExecutorException $e )
        {
        	$this->fail($e->getMessage());
        }
    }
    
}
?>