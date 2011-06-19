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

// TODO: Add in PHP Unit expected exception tests

class EnterpriseSecurityExceptionTest extends PHPUnit_Framework_TestCase
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
		$this->assertEquals( $e->getUserMessage(), null );
		$this->assertEquals( $e->getLogMessage(), '' );
	}

	function testEnterpriseSecurityException() {
		$e = new EnterpriseSecurityException("This is a message for users.","This is a message for the log.");
		$this->assertEquals( $e->getUserMessage(), "This is a message for users." );
		$this->assertEquals( $e->getLogMessage(), "This is a message for the log." );
	}
	
	function testAccessControlDefaultException() {
		$e = new AccessControlException();
		$this->assertEquals( $e->getUserMessage(), null );
		$this->assertEquals( $e->getLogMessage(), '' );
	}
	
	function testAccessControlException() {
		$e = new AccessControlException("This is a message for users.","This is a message for the log.");
		$this->assertEquals( $e->getUserMessage(), "This is a message for users." );
		$this->assertEquals( $e->getLogMessage(), "This is a message for the log." );
	}

	function testAuthenticationDefaultException() {
		$e = new AuthenticationException();
		$this->assertEquals( $e->getUserMessage(), null );
		$this->assertEquals( $e->getLogMessage(), '' );
	}
	
	function testAuthenticationException() {
		$e = new AuthenticationException("This is a message for users.","This is a message for the log.");
		$this->assertEquals( $e->getUserMessage(), "This is a message for users." );
		$this->assertEquals( $e->getLogMessage(), "This is a message for the log." );
	}

	function testAvailabilityDefaultException() {
		$e = new AvailabilityException();
		$this->assertEquals( $e->getUserMessage(), null );
		$this->assertEquals( $e->getLogMessage(), '' );
	}
	
	function testAvailabilityException() {
		$e = new AvailabilityException("This is a message for users.","This is a message for the log.");
		$this->assertEquals( $e->getUserMessage(), "This is a message for users." );
		$this->assertEquals( $e->getLogMessage(), "This is a message for the log." );
	}

	function testCertificateDefaultException() {
		$e = new CertificateException();
		$this->assertEquals( $e->getUserMessage(), null );
		$this->assertEquals( $e->getLogMessage(), '' );
	}
	
	function testCertificateException() {
		$e = new CertificateException("This is a message for users.","This is a message for the log.");
		$this->assertEquals( $e->getUserMessage(), "This is a message for users." );
		$this->assertEquals( $e->getLogMessage(), "This is a message for the log." );
	}

	function testEncodingDefaultException() {
		$e = new EncodingException();
		$this->assertEquals( $e->getUserMessage(), null );
		$this->assertEquals( $e->getLogMessage(), '' );
	}
		
	function testEncodingException() {
		$e = new EncodingException("This is a message for users.","This is a message for the log.");
		$this->assertEquals( $e->getUserMessage(), "This is a message for users." );
		$this->assertEquals( $e->getLogMessage(), "This is a message for the log." );
	}

	function testEncryptionDefaultException() {
		$e = new EncryptionException();
		$this->assertEquals( $e->getUserMessage(), null );
		$this->assertEquals( $e->getLogMessage(), '' );
	}
	
	function testEncryptionException() {
		$e = new EncryptionException("This is a message for users.","This is a message for the log.");
		$this->assertEquals( $e->getUserMessage(), "This is a message for users." );
		$this->assertEquals( $e->getLogMessage(), "This is a message for the log." );
	}
	
	function testExecutorDefaultException() {
		$e = new ExecutorException();
		$this->assertEquals( $e->getUserMessage(), null );
		$this->assertEquals( $e->getLogMessage(), '' );
	}
	
	function testExecutorException() {
		$e = new ExecutorException("This is a message for users.","This is a message for the log.");
		$this->assertEquals( $e->getUserMessage(), "This is a message for users." );
		$this->assertEquals( $e->getLogMessage(), "This is a message for the log." );
	}
	
	function testValidationDefaultException() {
		$e = new ValidationException();
		$this->assertEquals( $e->getUserMessage(), null );
		$this->assertEquals( $e->getLogMessage(), '' );
	}
	
	function testValidationException() {
		$e = new ValidationException("This is a message for users.","This is a message for the log.");
		$this->assertEquals( $e->getUserMessage(), "This is a message for users." );
		$this->assertEquals( $e->getLogMessage(), "This is a message for the log." );
	}
	
	function testValidationExceptionContext() {
		$e = new ValidationException();
		$e->setContext("test");
		$this->assertEquals( "test", $e->getContext() );
	}
	
	function testIntegrityDefaultException() {
		$e = new IntegrityException();
		$this->assertEquals( $e->getUserMessage(), null );
		$this->assertEquals( $e->getLogMessage(), '' );
	}
	
	function testIntegrityException() {
		$e = new IntegrityException("This is a message for users.","This is a message for the log.");
		$this->assertEquals( $e->getUserMessage(), "This is a message for users." );
		$this->assertEquals( $e->getLogMessage(), "This is a message for the log." );
	}
	
	function testAuthenticationHostDefaultException() {
		$e = new AuthenticationHostException();
		$this->assertEquals( $e->getUserMessage(), null );
		$this->assertEquals( $e->getLogMessage(), '' );
	}
	
	function testAuthenticationHostException() {
		$e = new AuthenticationHostException("This is a message for users.","This is a message for the log.");
		$this->assertEquals( $e->getUserMessage(), "This is a message for users." );
		$this->assertEquals( $e->getLogMessage(), "This is a message for the log." );
	}
	function testAuthenticationAccountsDefaultException() {
		$e = new AuthenticationAccountsException();
		$this->assertEquals( $e->getUserMessage(), null );
		$this->assertEquals( $e->getLogMessage(), '' );
	}
	
	function testAuthenticationAccountsException() {
		$e = new AuthenticationAccountsException("This is a message for users.","This is a message for the log.");
		$this->assertEquals( $e->getUserMessage(), "This is a message for users." );
		$this->assertEquals( $e->getLogMessage(), "This is a message for the log." );
	}
	
	function testAuthenticationCredentialsDefaultException() {
		$e = new AuthenticationCredentialsException();
		$this->assertEquals( $e->getUserMessage(), null );
		$this->assertEquals( $e->getLogMessage(), '' );
	}
	
	function testAuthenticationCredentialsException() {
		$e = new AuthenticationCredentialsException("This is a message for users.","This is a message for the log.");
		$this->assertEquals( $e->getUserMessage(), "This is a message for users." );
		$this->assertEquals( $e->getLogMessage(), "This is a message for the log." );
	}
	function testAuthenticationLoginDefaultException() {
		$e = new AuthenticationLoginException();
		$this->assertEquals( $e->getUserMessage(), null );
		$this->assertEquals( $e->getLogMessage(), '' );
	}
	
	function testAuthenticationLoginException() {
		$e = new AuthenticationLoginException("This is a message for users.","This is a message for the log.");
		$this->assertEquals( $e->getUserMessage(), "This is a message for users." );
		$this->assertEquals( $e->getLogMessage(), "This is a message for the log." );
	}
	
	function testValidationAvailabilityDefaultException() {
		$e = new ValidationAvailabilityException();
		$this->assertEquals( $e->getUserMessage(), null );
		$this->assertEquals( $e->getLogMessage(), '' );
	}
	
	function testValidationAvailabilityException() {
		$e = new ValidationAvailabilityException("This is a message for users.","This is a message for the log.");
		$this->assertEquals( $e->getUserMessage(), "This is a message for users." );
		$this->assertEquals( $e->getLogMessage(), "This is a message for the log." );
	}

	function testValidationUploadDefaultException() {
		$e = new ValidationUploadException();
		$this->assertEquals( $e->getUserMessage(), null );
		$this->assertEquals( $e->getLogMessage(), '' );
	}
	function testValidationUploadException() {
		$e = new ValidationUploadException("This is a message for users.","This is a message for the log.");
		$this->assertEquals( $e->getUserMessage(), "This is a message for users." );
		$this->assertEquals( $e->getLogMessage(), "This is a message for the log." );
	}

	function testIntrusionDefaultException() {
		$e = new IntrusionException();
		$this->assertEquals( $e->getUserMessage(), null );
		$this->assertEquals( $e->getLogMessage(), '' );
	}
	
	function testIntrusionException() {
		$e = new IntrusionException("This is a message for users.","This is a message for the log.");
		$this->assertEquals( $e->getUserMessage(), "This is a message for users." );
		$this->assertEquals( $e->getLogMessage(), "This is a message for the log." );
	}
}
?>