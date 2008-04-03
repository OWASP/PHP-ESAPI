<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * http://www.owasp.org/esapi.
 *
 * Copyright (c) 2007 - The OWASP Foundation
 *
 * The ESAPI is published by OWASP under the LGPL. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 *
 * @author Jeff Williams <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @author Andrew van der Stock <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @package org.owasp.esapi.errors;
 * @since 2008
 */

require_once ("../../src/errors/org.owasp.esapi.AccessControlException.php");
require_once ("../../src/errors/org.owasp.esapi.AuthenticationException.php");
require_once ("../../src/errors/org.owasp.esapi.AvailabilityException.php");
require_once ("../../src/errors/org.owasp.esapi.CertificateException.php");
require_once ("../../src/errors/org.owasp.esapi.EncryptionException.php");
require_once ("../../src/errors/org.owasp.esapi.EnterpriseSecurityException.php");
require_once ("../../src/errors/org.owasp.esapi.ExecutorException.php");
require_once ("../../src/errors/org.owasp.esapi.IntrusionException.php");
require_once ("../../src/errors/org.owasp.esapi.ValidationException.php");

/**
 * The Class AccessReferenceMapTest.
 *
 * @author Jeff Williams (jeff.williams@aspectsecurity.com)
 */
class EnterpriseSecurityExceptionTest extends TestCase
{
    /**
     * Instantiates a new access reference map test.
     *
     * @param testName
     *            the test name
     */
    function EnterpriseSecurityExceptionTest()
    {
    }
    /* (non-Javadoc)
     * @see junit.framework.TestCase#setUp()
     */
    function setUp()
    {
        // none
    }
    /* (non-Javadoc)
     * @see junit.framework.TestCase#tearDown()
     */
    function tearDown()
    {
        // none
    }
    /**
     * Test of update method, of class org.owasp.esapi.AccessReferenceMap.
     *
     * @throws AuthenticationException
     *             the authentication exception
     */
    public function testExceptions()
    {
        echo ("exceptions");
        $e = null;
        $e = new EnterpriseSecurityException();
        $e = new EnterpriseSecurityException("m1", "m2");
        $e = new EnterpriseSecurityException("m1", "m2", new Throwable());
        $this->assertEquals($e->getUserMessage(), "m1");
        $this->assertEquals($e->getLogMessage(), "m2");
        $e = new AccessControlException();
        $e = new AccessControlException("m1", "m2");
        $e = new AccessControlException("m1", "m2", new Throwable());
        $e = new AuthenticationException();
        $e = new AuthenticationException("m1", "m2");
        $e = new AuthenticationException("m1", "m2", new Throwable());
        $e = new AvailabilityException();
        $e = new AvailabilityException("m1", "m2");
        $e = new AvailabilityException("m1", "m2", new Throwable());
        $e = new CertificateException();
        $e = new CertificateException("m1", "m2");
        $e = new CertificateException("m1", "m2", new Throwable());
        $e = new EncodingException();
        $e = new EncodingException("m1", "m2");
        $e = new EncodingException("m1", "m2", new Throwable());
        $e = new EncryptionException();
        $e = new EncryptionException("m1", "m2");
        $e = new EncryptionException("m1", "m2", new Throwable());
        $e = new ExecutorException();
        $e = new ExecutorException("m1", "m2");
        $e = new ExecutorException("m1", "m2", new Throwable());
        $e = new ValidationException();
        $e = new ValidationException("m1", "m2");
        $e = new ValidationException("m1", "m2", new Throwable());
        $e = new AuthenticationAccountsException();
        $e = new AuthenticationAccountsException("m1", "m2");
        $e = new AuthenticationAccountsException("m1", "m2", new Throwable());
        $e = new AuthenticationCredentialsException();
        $e = new AuthenticationCredentialsException("m1", "m2");
        $e = new AuthenticationCredentialsException("m1", "m2", new Throwable());
        $e = new AuthenticationLoginException();
        $e = new AuthenticationLoginException("m1", "m2");
        $e = new AuthenticationLoginException("m1", "m2", new Throwable());
        $e = new ValidationAvailabilityException();
        $e = new ValidationAvailabilityException("m1", "m2");
        $e = new ValidationAvailabilityException("m1", "m2", new Throwable());
        $e = new ValidationUploadException();
        $e = new ValidationUploadException("m1", "m2");
        $e = new ValidationUploadException("m1", "m2", new Throwable());
        $ex = new IntrusionException();
        $ex = new IntrusionException("m1", "m2");
        $ex = new IntrusionException("m1", "m2", new Throwable());
        $this->assertEquals($ex->getUserMessage(), "m1");
        $this->assertEquals($ex->getLogMessage(), "m2");
    }
}
?>