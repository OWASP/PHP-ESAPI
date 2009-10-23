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

	function testEnterpriseSecurityDefaultException() {
		$e = new EnterpriseSecurityException();
		$this->assertEqual( $e->getUserMessage(), null );
		$this->assertEqual( $e->getLogMessage(), '' );
	}

	function testEnterpriseSecurityException() {
		$e = new EnterpriseSecurityException("m1","m2");
		$this->assertEqual( $e->getUserMessage(), "m1" );
		$this->assertEqual( $e->getLogMessage(), "m2" );
	}
	
	function testAccessControlDefaultException() {
		$e = new AccessControlException();
		$this->assertEqual( $e->getUserMessage(), null );
		$this->assertEqual( $e->getLogMessage(), '' );
	}
	
	function testAccessControlException() {
		$e = new AccessControlException("m1","m2");
		$this->assertEqual( $e->getUserMessage(), "m1" );
		$this->assertEqual( $e->getLogMessage(), "m2" );
	}

	function testAuthenticationDefaultException() {
		$e = new AuthenticationException();
		$this->assertEqual( $e->getUserMessage(), null );
		$this->assertEqual( $e->getLogMessage(), '' );
	}
	
	function testAuthenticationException() {
		$e = new AuthenticationException("m1","m2");
		$this->assertEqual( $e->getUserMessage(), "m1" );
		$this->assertEqual( $e->getLogMessage(), "m2" );
	}

	function testAvailabilityDefaultException() {
		$e = new AvailabilityException();
		$this->assertEqual( $e->getUserMessage(), null );
		$this->assertEqual( $e->getLogMessage(), '' );
	}
	
	function testAvailabilityException() {
		$e = new AvailabilityException("m1","m2");
		$this->assertEqual( $e->getUserMessage(), "m1" );
		$this->assertEqual( $e->getLogMessage(), "m2" );
	}

	function testCertificateDefaultException() {
		$e = new CertificateException();
		$this->assertEqual( $e->getUserMessage(), null );
		$this->assertEqual( $e->getLogMessage(), '' );
	}
	
	function testCertificateException() {
		$e = new CertificateException("m1","m2");
		$this->assertEqual( $e->getUserMessage(), "m1" );
		$this->assertEqual( $e->getLogMessage(), "m2" );
	}

	function testEncodingDefaultException() {
		$e = new EncodingException();
		$this->assertEqual( $e->getUserMessage(), null );
		$this->assertEqual( $e->getLogMessage(), '' );
	}
		
	function testEncodingException() {
		$e = new EncodingException("m1","m2");
		$this->assertEqual( $e->getUserMessage(), "m1" );
		$this->assertEqual( $e->getLogMessage(), "m2" );
	}

	function testEncryptionDefaultException() {
		$e = new EncryptionException();
		$this->assertEqual( $e->getUserMessage(), null );
		$this->assertEqual( $e->getLogMessage(), '' );
	}
	
	function testEncryptionException() {
		$e = new EncryptionException("m1","m2");
		$this->assertEqual( $e->getUserMessage(), "m1" );
		$this->assertEqual( $e->getLogMessage(), "m2" );
	}
	
	function testExecutorDefaultException() {
		$e = new ExecutorException();
		$this->assertEqual( $e->getUserMessage(), null );
		$this->assertEqual( $e->getLogMessage(), '' );
	}
	
	function testExecutorException() {
		$e = new ExecutorException("m1","m2");
		$this->assertEqual( $e->getUserMessage(), "m1" );
		$this->assertEqual( $e->getLogMessage(), "m2" );
	}
	
	function testValidationDefaultException() {
		$e = new ValidationException();
		$this->assertEqual( $e->getUserMessage(), null );
		$this->assertEqual( $e->getLogMessage(), '' );
	}
	
	function testValidationException() {
		$e = new ValidationException("m1","m2");
		$this->assertEqual( $e->getUserMessage(), "m1" );
		$this->assertEqual( $e->getLogMessage(), "m2" );
	}
	
	function testValidationExceptionContext() {
		$e = new ValidationException();
		$e->setContext("test");
		$this->assertEqual( "test", $e->getContext() );
	}
	
	function testIntegrityDefaultException() {
		$e = new IntegrityException();
		$this->assertEqual( $e->getUserMessage(), null );
		$this->assertEqual( $e->getLogMessage(), '' );
	}
	
	function testIntegrityException() {
		$e = new IntegrityException("m1","m2");
		$this->assertEqual( $e->getUserMessage(), "m1" );
		$this->assertEqual( $e->getLogMessage(), "m2" );
	}
	
	function testAuthenticationHostDefaultException() {
		$e = new AuthenticationHostException();
		$this->assertEqual( $e->getUserMessage(), null );
		$this->assertEqual( $e->getLogMessage(), '' );
	}
	
	function testAuthenticationHostException() {
		$e = new AuthenticationHostException("m1","m2");
		$this->assertEqual( $e->getUserMessage(), "m1" );
		$this->assertEqual( $e->getLogMessage(), "m2" );
	}
	function testAuthenticationAccountsDefaultException() {
		$e = new AuthenticationAccountsException();
		$this->assertEqual( $e->getUserMessage(), null );
		$this->assertEqual( $e->getLogMessage(), '' );
	}
	
	function testAuthenticationAccountsException() {
		$e = new AuthenticationAccountsException("m1","m2");
		$this->assertEqual( $e->getUserMessage(), "m1" );
		$this->assertEqual( $e->getLogMessage(), "m2" );
	}
	
	function testAuthenticationCredentialsDefaultException() {
		$e = new AuthenticationCredentialsException();
		$this->assertEqual( $e->getUserMessage(), null );
		$this->assertEqual( $e->getLogMessage(), '' );
	}
	
	function testAuthenticationCredentialsException() {
		$e = new AuthenticationCredentialsException("m1","m2");
		$this->assertEqual( $e->getUserMessage(), "m1" );
		$this->assertEqual( $e->getLogMessage(), "m2" );
	}
	function testAuthenticationLoginDefaultException() {
		$e = new AuthenticationLoginException();
		$this->assertEqual( $e->getUserMessage(), null );
		$this->assertEqual( $e->getLogMessage(), '' );
	}
	
	function testAuthenticationLoginException() {
		$e = new AuthenticationLoginException("m1","m2");
		$this->assertEqual( $e->getUserMessage(), "m1" );
		$this->assertEqual( $e->getLogMessage(), "m2" );
	}
	
	function testValidationAvailabilityDefaultException() {
		$e = new ValidationAvailabilityException();
		$this->assertEqual( $e->getUserMessage(), null );
		$this->assertEqual( $e->getLogMessage(), '' );
	}
	
	function testValidationAvailabilityException() {
		$e = new ValidationAvailabilityException("m1","m2");
		$this->assertEqual( $e->getUserMessage(), "m1" );
		$this->assertEqual( $e->getLogMessage(), "m2" );
	}

	function testValidationUploadDefaultException() {
		$e = new ValidationUploadException();
		$this->assertEqual( $e->getUserMessage(), null );
		$this->assertEqual( $e->getLogMessage(), '' );
	}
	function testValidationUploadException() {
		$e = new ValidationUploadException("m1","m2");
		$this->assertEqual( $e->getUserMessage(), "m1" );
		$this->assertEqual( $e->getLogMessage(), "m2" );
	}

	function testIntrusionDefaultException() {
		$e = new IntrusionException();
		$this->assertEqual( $e->getUserMessage(), null );
		$this->assertEqual( $e->getLogMessage(), '' );
	}
	
	function testIntrusionException() {
		$e = new IntrusionException("m1","m2");
		$this->assertEqual( $e->getUserMessage(), "m1" );
		$this->assertEqual( $e->getLogMessage(), "m2" );
	}
}
?>