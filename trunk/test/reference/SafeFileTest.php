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
 * @author Andrew van der Stock (vanderaj @ owasp.org), Arnaud Labenne - dotsafe.fr
 * @created 2010
 */

require_once dirname(__FILE__).'/../../src/ESAPI.php';
require_once dirname(__FILE__).'/../../src/SafeFile.php';

class SafeFileTest extends UnitTestCase 
{
	function setUp() 
	{
        global $ESAPI;

        if (!isset($ESAPI)) {
            $ESAPI = new ESAPI(dirname(__FILE__).'/../testresources/ESAPI.xml');
        }
	}
	
	function tearDown()
	{
		
	}
	
	function testSafeFile()
	{
	    $config = ESAPI::getSecurityConfiguration();
		$file = $config->getResourceDirectory() . "/ESAPI.xml";
		
		$sf = new SafeFile($file);
		
		if (!$sf->isReadable()) {
		    $this->fail();
		}
	}
	
	function testSafeFileWithNullByte()
	{
	    $config = ESAPI::getSecurityConfiguration();
		$file = $config->getResourceDirectory() . "/ESAPI.xml" . chr(0);
		
		try{
		    $sf = new SafeFile($file);
		    $this->fail();
		} catch(Exception $e) {
		    //Expected
		}
		
		$file = $config->getResourceDirectory() . chr(0) . "/ESAPI.xml";
		
		try{
		    $sf = new SafeFile($file);
		    $this->fail();
		} catch(Exception $e) {
		    //Expected
		}
	}
	
	function testSafeFileWithPercentEncoding()
	{
	    $config = ESAPI::getSecurityConfiguration();
	    
	    $tests = array("%00", "%3C", "%3c", "%Ac");
	    
	    foreach ($tests as $percent) {
			$file = $config->getResourceDirectory() . "/ESAPI.xml$percent";
			echo "$file<br/>"; 
	        try{
			    $sf = new SafeFile($file);
			    $this->fail();
			} catch(Exception $e) {
			    //Expected
			}
	    }
	    
		$file = $config->getResourceDirectory() . "%00/ESAPI.xml";
		
        try{
		    $sf = new SafeFile($file);
		    $this->fail();
		} catch(Exception $e) {
		    //Expected
		}
	}
	
	function testSafeFileIllegalCharacter()
	{
	    $fileIllegals = array('/', ':', '*', '?', '<', '>', '|', '\\');
	    $dirIllegals = array('*', '?', '<', '>', '|');
	    
	    $config = ESAPI::getSecurityConfiguration();
	    
	    foreach ($fileIllegals as $char) {
			$file = $config->getResourceDirectory() . "/ESAPI$char.xml";
			
	        try{
			    $sf = new SafeFile($file);
			    $this->fail();
			} catch(Exception $e) {
			    //Expected
			}
	    }
		
	    foreach ($dirIllegals as $char) {
			$file = $config->getResourceDirectory() . "$char/ESAPI.xml";
			
	        try{
			    $sf = new SafeFile($file);
			    $this->fail();
			} catch(Exception $e) {
			    //Expected
			}
	    }
	}
	
	function testSafeFileHighByte()
	{
	    $config = ESAPI::getSecurityConfiguration();
		$file = $config->getResourceDirectory() . "/ESAPI" . chr(200) . ".xml";
		
        try{
		    $sf = new SafeFile($file);
		    $this->fail();
		} catch(Exception $e) {
		    //Expected
		}
		
		$file = $config->getResourceDirectory() . chr(200) . "/ESAPI.xml";
		
        try{
		    $sf = new SafeFile($file);
		    $this->fail();
		} catch(Exception $e) {
		    //Expected
		}
	}
	
	function testSafeFileLowByte()
	{
	    $config = ESAPI::getSecurityConfiguration();
		$file = $config->getResourceDirectory() . "/ESAPI" . chr(8) . ".xml";
		
        try{
		    $sf = new SafeFile($file);
		    $this->fail();
		} catch(Exception $e) {
		    //Expected
		}
		
		$file = $config->getResourceDirectory() . chr(8) . "/ESAPI.xml";
		
        try{
		    $sf = new SafeFile($file);
		    $this->fail();
		} catch(Exception $e) {
		    //Expected
		}
	}
	
	function testURI()
	{		
		$file = 'file:///etc/passwd';
		
	    try{
		    $sf = new SafeFile($file);
		    		    		    
		} catch(Exception $e) {
		    $this->fail();
		}
		
		$file = 'file:///etc/passwd' . chr(0) . '/test.php';
		
        try{
		    $sf = new SafeFile($file);
		    $this->fail();
		} catch(Exception $e) {
		    //Expected
		}		
		
		$file = 'http://www.google.com/index.html' .chr(0);
		
        try{
		    $sf = new SafeFile($file);
		    $this->fail();
		} catch(Exception $e) {
		    //Expected
		}
	}
	
	function testDetectForbiddenCharacter()
	{   
	    $config = ESAPI::getSecurityConfiguration();
	    
	    for ($i = 0 ; $i < 256 ; $i++) {
			$file = $config->getResourceDirectory() . "/ESAPI.xml" . chr($i);
			
			try {
			    @$f = new SplFileObject($file);
			    if ($f->isReadable()) {
			        
			        try {
			            $sf = new SafeFile($file);			           
			            $this->fail();
			            
			        } catch (Exception $e) {
			            //Expected
			        }
			        
			    }
			} catch (Exception $e) {
			    //Expected
			}
	    }
	}
}
?>