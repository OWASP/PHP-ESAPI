<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project.
 *
 * PHP version 5.2
 *
 * LICENSE: This source file is subject to the New BSD license.  You should read
 * and accept the LICENSE before you use, modify, and/or redistribute this
 * software.
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   SVN: $Id$
 * @link      http://www.owasp.org/index.php/ESAPI
 */


/**
 * HttpUtilitiesTest requires SafeRequest.
 */
require_once dirname(__FILE__) . '/../../src/filters/SafeRequest.php';


/**
 * Tests for the reference implementation of the HTTPUtilities interface.
 * Note that it is not possible to modify headers within a simpletest UnitTestCase
 * because simpletest painters will have already caused the headers to be sent.
 * Therefore there are no tests for:
 * changeSessionIdentifier
 * killCookie and killAllCookies
 * Additionally SafeRequest currently obtains Request Parameters directly from
 * $_GET and $_POST so that it is not possible to test:
 * verifyCSRFToken
 * logHTTPRequest and logHTTPRequestObfuscate
 *
 * PHP version 5.2
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   Release: @package_version@
 * @link      http://www.owasp.org/index.php/ESAPI
 */
class HttpUtilitiesTest extends UnitTestCase
{
    private $_httpUtils = null;

    /**
     * Constructor ensures global ESAPI is set and stores an instance of
     * DefaultHTTPUtilities.
     * 
     * @return null
     */
    public function __construct()
    {
        $this->_httpUtils = ESAPI::getHTTPUtilities();
    }


    /**
     * Test of addCSRFToken method of class HTTPUtilities.
     *
     * @return bool True on Pass.
     */
    function testAddCSRFToken()
    {
        $csrf1 = $this->_httpUtils->addCSRFToken("/test1");
        $this->assertTrue(
            preg_match(
                '/^\x2ftest1\?[a-z0-9]{8,8}(?:\-[a-z0-9]{4,4}){3,3}\-[a-z0-9]{12,12}$/',
                $csrf1
            ) === 1
        );

        $csrf2 = $this->_httpUtils->addCSRFToken("/test2?one=two");
        $this->assertTrue(
            preg_match(
                '/^\x2ftest2\?one=two&[a-z0-9]{8,8}(?:\-[a-z0-9]{4,4}){3,3}\-[a-z0-9]{12,12}$/',
                $csrf2
            ) === 1
        );
    }


    /**
     * Test of getCookie method of class HTTPUtilities.
     *
     * @return bool True on Pass.
     */
    function testGetCookie()
    {
        $req = new SafeRequest(
            array(
                'cookies' => array(
                    'foo' => 'bar'
                )
            )
        );
        $this->assertEqual('bar', $this->_httpUtils->getCookie($req, 'foo'));
    }


    /**
     * Test of assertSecureRequest method of class HTTPUtilities.
     * All prerequisites for a secure request are met in this test.
     *
     * @return bool True on Pass.
     */
    function testAssertSecureRequestInputSecure()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'HTTPS' => '1',
                    'REQUEST_METHOD' => 'POST'
                )
            )
        );
        $this->assertNull($this->_httpUtils->assertSecureRequest($req));
    }


    /**
     * Test of assertSecureRequest method of class HTTPUtilities.
     * No TLS.
     *
     * @return bool True on Pass.
     */
    function testAssertSecureRequestInputInsecureNoTLS()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'HTTPS' => 'off',
                    'REQUEST_METHOD' => 'POST'
                )
            )
        );
        $this->expectException('AccessControlException');
        $this->_httpUtils->assertSecureRequest($req);
    }


    /**
     * Test of assertSecureRequest method of class HTTPUtilities.
     * No TLS.
     *
     * @return bool True on Pass.
     */
    function testAssertSecureRequestInputInsecureNoTLSAlt()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'HTTPS' => '0',
                    'REQUEST_METHOD' => 'POST'
                )
            )
        );
        $this->expectException('AccessControlException');
        $this->_httpUtils->assertSecureRequest($req);
    }


    /**
     * Test of assertSecureRequest method of class HTTPUtilities.
     * Not POST.
     *
     * @return bool True on Pass.
     */
    function testAssertSecureRequestInputInsecureNotPOST()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'HTTPS' => '1',
                    'REQUEST_METHOD' => 'GET'
                )
            )
        );
        $this->expectException('AccessControlException');
        $this->_httpUtils->assertSecureRequest($req);
    }


    /**
     * Test of assertSecureRequest method of class HTTPUtilities.
     * HTTPS env var not available
     *
     * @return bool True on Pass.
     */
    function testAssertSecureRequestInputCannotCheck()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'REQUEST_METHOD' => 'POST'
                )
            )
        );
        $this->expectException('EnterpriseSecurityException');
        $this->_httpUtils->assertSecureRequest($req);
    }

}
