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
 * @since 1.6
 */
 
require_once dirname(__FILE__).'/../../src/ESAPI.php';
require_once dirname(__FILE__).'/../../src/reference/DefaultRandomizer.php';
 
class RandomizerTest extends UnitTestCase 
{
	private $CHAR_ALPHANUMERICS = 'abcdefghijklmnopqrstuvxyzABCDEFGHIJKLMNOPQRSTUVXYZ01234567890';
	
	function setUp() 
	{
		global $ESAPI;
		
		if ( !isset($ESAPI)) 
		{
			$ESAPI = new ESAPI(dirname(__FILE__).'/../testresources/ESAPI.xml');
		}	
	}
	
	function tearDown()
	{
		
	}

    /**
     * Test of getRandomGUID method, of class org.owasp.esapi.Randomizer.
     * @throws EncryptionException
     */
    function testGetRandomGUID() {
        
        $instance = ESAPI::getRandomizer();
        
        $list = array();
        
        $result = true;
        for ( $i = 0; $i < 100; $i++ ) {
            $guid = $instance->getRandomGUID();
            if ( in_array($guid, $list) )
            {
            	$result = false;
				$this->fail();     	
            } 

            $list[] = $guid;
        }
        
        if ( $result ) 
        {
        	$this->pass();	
        }
    }	
	
	/**
	 * Test of getRandomString method, of class org.owasp.esapi.Randomizer.
	 */
    function testGetRandomString() {
        
        $length = 20;
        $instance = ESAPI::getRandomizer();
        $charset = str_split($this->CHAR_ALPHANUMERICS);
        
        try {
	        for ( $i = 0; $i < 100; $i++ ) {
	            $result = $instance->getRandomString($length, $this->CHAR_ALPHANUMERICS);	// TODO replace with DefaultEncoder...
	            
	            for ( $j=0; $j< strlen($result); $j++ ) {
	            	
	            	if ( !in_array($result[$j], $charset) ) {			// TODO replace with DefaultEncoder...
	            		$this->fail("Character [ ".$result[$j]." ] not found in [ ".$result." ]");
	            	}
	            }
	            $this->assertEqual($length, strlen($result));
	        }
        }
		catch (InvalidArgumentException $e)
		{
			$this->fail("getRandomString() failed due to too short length ($length) or no character set [ ".$this->CHAR_ALPHANUMERICS." ]");
		}		        
    }

    /**
	 * Test of getRandomInteger method, of class org.owasp.esapi.Randomizer.
	 */
    function testGetRandomInteger() {
        
        $min = -20;
        $max = 100;
        
        $instance = ESAPI::getRandomizer();        
        
        $minResult = ( $max - $min ) / 2;
        $maxResult = ( $max - $min ) / 2;
        
        for ( $i = 0; $i < 100; $i++ ) {
            $result = $instance->getRandomInteger($min, $max);
            if ( $result < $minResult ) 
            {
            	$minResult = $result;	
            }
            if ( $result > $maxResult ) 
			{
				$maxResult = $result;
			}
        }
        $this->assertTrue( ($minResult >= $min && $maxResult <= $max), "minResult ($minResult) >= min ($min) && maxResult ($maxResult) <= max ($max)" );
    }

    /**
	 * Test of getRandomReal method, of class org.owasp.esapi.Randomizer.
	 */
    function testGetRandomReal() {
        
        $min = -20.5234;
        $max = 100.12124;
        
        $instance = ESAPI::getRandomizer();
        
        $minResult = ( $max - $min ) / 2;
        $maxResult = ( $max - $min ) / 2;
        
        for ( $i = 0; $i < 100; $i++ ) {
            $result = $instance->getRandomReal($min, $max);
            if ( $result < $minResult ) 
            {
            	$minResult = $result;
            }
            if ( $result > $maxResult ) 
            {
            	$maxResult = $result;
            }
        }
        $this->assertTrue(($minResult >= $min && $maxResult <= $max));
    }
    
    function testGetRandomBoolean() {
    	$instance = ESAPI::getRandomizer();
    	
    	$result = $instance->getRandomBoolean();
    	
    	// PHP funkyness: I am using the equal operator with the type equivalence extra '='
    	// If both true and false are not found, then we don't have a boolean
    	$this->assertFalse($result !== true && $result !== false);
    }
    
    function testGetRandomLong() {
    	$instance = ESAPI::getRandomizer();
    	$result = $instance->getRandomLong();
    	
    	$this->assertTrue($result >= 0);
    	$this->assertTrue($result < mt_getrandmax());
    }
    
    function testGetRandomFilenameCharSet() {

        $instance = ESAPI::getRandomizer();
        $charset = str_split('abcdefghijklmnopqrstuvxyz0123456789'); // TODO replace with DefaultEncoder...
        
        try {
	        for ( $i = 0; $i < 100; $i++ ) {
	            $result = $instance->getRandomFilename();
	            $len = strlen($result);		// Filenames should be 16 characters long
	            
	            for ( $j = 0; $j < $len; $j++ ) {
	            	if ( !in_array($result[$j], $charset) ) {			
	            		$this->fail("Character [ ".$result[$j]." ] not found in [ ".$result." ]");
	            	}
	            }
	            
	        }
        }
		catch (InvalidArgumentException $e)
		{
			$this->fail("getRandomFilename() failed due to too short length (16) or no character set [ abcdefghijklmnopqrstuvxyz0123456789 ]");
		}	
    }
    
	function testGetRandomFilenameLengthNoExtension() {

        $instance = ESAPI::getRandomizer();
        
        $result = $instance->getRandomFilename();
        $this->assertEqual(16, strlen($result));
    }

    function testGetRandomFilenameLengthWithExtension() {

        $instance = ESAPI::getRandomizer();
        
        $result = $instance->getRandomFilename('.php');
        $this->assertEqual(20, strlen($result));
    }
    
}
?>