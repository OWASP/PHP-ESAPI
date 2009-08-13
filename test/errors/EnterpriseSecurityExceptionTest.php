<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 * 
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * <a href="http://www.owasp.org/index.php/ESAPI">http://www.owasp.org/index.php/ESAPI</a>.
 *
 * Copyright (c) 2007 - The OWASP Foundation
 * 
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 * 
 * @author Jeff Williams <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @created 2007
 */
 
require_once dirname(__FILE__).'/../../src/ESAPI.php';
require_once dirname(__FILE__).'/../../src/errors/AccessControlException.php';
require_once dirname(__FILE__).'/../../src/errors/AuthenticationAccountsException.php';
require_once dirname(__FILE__).'/../../src/errors/AuthenticationCredentialsException.php';
require_once dirname(__FILE__).'/../../src/errors/AuthenticationHostException.php';
require_once dirname(__FILE__).'/../../src/errors/AuthenticationLoginException.php';
require_once dirname(__FILE__).'/../../src/errors/AvailabilityException.php';
require_once dirname(__FILE__).'/../../src/errors/CertificateException.php';
require_once dirname(__FILE__).'/../../src/errors/EncodingException.php';
require_once dirname(__FILE__).'/../../src/errors/EncryptionException.php';
require_once dirname(__FILE__).'/../../src/errors/ExecutorException.php';
require_once dirname(__FILE__).'/../../src/errors/IntegrityException.php';
require_once dirname(__FILE__).'/../../src/errors/ValidationAvailabilityException.php';
require_once dirname(__FILE__).'/../../src/errors/ValidationUploadException.php';
 
class EnterpriseSecurityExceptionTest extends UnitTestCase 
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
	
	function testExceptions() {
        $e = null;
        
        $e = new EnterpriseSecurityException();
        $e = new EnterpriseSecurityException("m1","m2");
        
        $this->assertEqual( $e->getUserMessage(), "m1" );
        $this->assertEqual( $e->getLogMessage(), "m2" );
        
        $e = new AccessControlException();
        $e = new AccessControlException("m1","m2");
        $e = new AuthenticationException();
        $e = new AuthenticationException("m1","m2");
        
		$e = new AvailabilityException();
        $e = new AvailabilityException("m1","m2");

        $e = new CertificateException();
        $e = new CertificateException("m1","m2");
        
        
        $e = new EncodingException();
        $e = new EncodingException("m1","m2");
        
        $e = new EncryptionException();
        $e = new EncryptionException("m1","m2");
        
        $e = new ExecutorException();
        $e = new ExecutorException("m1","m2");
        
        $e = new ValidationException();
        $e = new ValidationException("m1","m2");
         
        $e = new IntegrityException();
        $e = new IntegrityException("m1","m2");
        
        $e = new AuthenticationHostException();
        $e = new AuthenticationHostException("m1","m2");

        $e = new AuthenticationAccountsException();
        $e = new AuthenticationAccountsException("m1","m2");
        
        $e = new AuthenticationCredentialsException();
        $e = new AuthenticationCredentialsException("m1","m2");
        
        $e = new AuthenticationLoginException();
        $e = new AuthenticationLoginException("m1","m2");
        
        $e = new ValidationAvailabilityException();
        $e = new ValidationAvailabilityException("m1","m2");
        
        $e = new ValidationUploadException();
        $e = new ValidationUploadException("m1","m2");

        $ve = new ValidationException();
        $ve->setContext("test");
        $this->assertEqual( "test", $ve->getContext() );

		$ex = new IntrusionException( "test", "test details");
        
        $ex = new IntrusionException("m1","m2");
        $this->assertEqual( $ex->getUserMessage(), "m1" );
        $this->assertEqual( $ex->getLogMessage(), "m2" );
	}
}
?>