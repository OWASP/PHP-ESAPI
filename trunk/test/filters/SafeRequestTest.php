<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project.
 *
 * LICENSE: This source file is subject to the New BSD license.  You should read
 * and accept the LICENSE before you use, modify, and/or redistribute this
 * software.
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */


/**
 *
 */
require_once dirname(__FILE__) . '/../../src/ESAPI.php';
require_once dirname(__FILE__) . '/../../src/filters/SafeRequest.php';


/**
 * UnitTestCase for SafeRequest implementation.
 *
 * PHP version 5.2.9
 *
 * @category  OWASP
 * @package   ESAPI
 * @version   1.0
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */
class SafeRequestTest extends UnitTestCase
{
    function __construct()
    {
        global $ESAPI;
        if ( !isset($ESAPI))
        {
            $ESAPI = new ESAPI(dirname(__FILE__) . '/../../testresources/ESAPI.xml');
        }
    }


    /**
     * Test of SafeRequest::getAuthType() with null input.
     */
    function testGetAuthType_Null()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                )
            )
        );
        $result = $req->getAuthType();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);
    }


    /**
     * Test of SafeRequest::getAuthType() with invalid input.
     */
    function testGetAuthType_Invalid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'AUTH_TYPE' => 'B-asic'
                )
            )
        );
        $result = $req->getAuthType();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);
    }


    /**
     * Test of SafeRequest::getAuthType() with valid input.
     */
    function testGetAuthType_Valid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'AUTH_TYPE' => 'bAsic'
                )
            )
        );
        $result = $req->getAuthType();
        $this->assertIsA($result, 'string');
        $this->assertEqual('bAsic', $result);
    }


    /**
     * Test of SafeRequest::getContentLength() with null input.
     */
    function testGetContentLength_Null()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                )
            )
        );
        $result = $req->getContentLength();
        $this->assertIsA($result, 'int');
        $this->assertEqual(0, $result);
    }


    /**
     * Test of SafeRequest::getContentLength() with invalid input.
     */
    function testGetContentLength_Invalid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'CONTENT_LENGTH' => '-1'
                )
            )
        );
        $result = $req->getContentLength();
        $this->assertIsA($result, 'int');
        $this->assertEqual(0, $result);
    }


    /**
     * Test of SafeRequest::getContentLength() with valid input.
     */
    function testGetContentLength_Valid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'CONTENT_LENGTH' => '1024'
                )
            )
        );
        $result = $req->getContentLength();
        $this->assertIsA($result, 'int');
        $this->assertEqual(1024, $result);
    }


    /**
     * Test of SafeRequest::getContentType() with null input.
     */
    function testGetContentType_Null()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                )
            )
        );
        $result = $req->getContentType();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);
    }


    /**
     * Test of SafeRequest::getContentType() with invalid input.
     */
    function testGetContentType_Invalid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'CONTENT_TYPE' => 'application/ürl-form-encoded'
                )
            )
        );
        $result = $req->getContentType();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);
    }


    /**
     * Test of SafeRequest::getContentType() with valid input.
     */
    function testGetContentType_Valid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'CONTENT_TYPE' => 'application/url-form-encoded'
                )
            )
        );
        $result = $req->getContentType();
        $this->assertIsA($result, 'string');
        $this->assertEqual('application/url-form-encoded', $result);
    }


    /**
     * Test of SafeRequest::getPathInfo() with null input.
     */
    function testGetPathInfo_Null()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                )
            )
        );
        $result = $req->getPathInfo();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);
    }


    /**
     * Test of SafeRequest::getPathInfo() with invalid input.
     */
    function testGetPathInfo_Invalid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'PATH_INFO' => '/foo%00'
                )
            )
        );
        $result = $req->getPathInfo();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);
    }


    /**
     * Test of SafeRequest::getPathInfo() with valid input.
     */
    function testGetPathInfo_Valid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'PATH_INFO' => '/foo'
                )
            )
        );
        $result = $req->getPathInfo();
        $this->assertIsA($result, 'string');
        $this->assertEqual('/foo', $result);
    }


    /**
     * Test of SafeRequest::getPathTranslated() with null input.
     */
    function testGetPathTranslated_Null()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                )
            )
        );
        $result = $req->getPathTranslated();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);
    }


    /**
     * Test of SafeRequest::getPathTranslated() with invalid input.
     */
    function testGetPathTranslated_Invalid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'PATH_TRANSLATED' => '/foo%00'
                )
            )
        );
        $result = $req->getPathTranslated();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);
    }


    /**
     * Test of SafeRequest::getPathTranslated() with valid input.
     */
    function testGetPathTranslated_Valid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'PATH_TRANSLATED' => '/foo'
                )
            )
        );
        $result = $req->getPathTranslated();
        $this->assertIsA($result, 'string');
        $this->assertEqual('/foo', $result);
    }


    /**
     * Test of SafeRequest::getQueryString() with null input.
     */
    function testGetQueryString_Null()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                )
            )
        );
        $result = $req->getQueryString();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);
    }


    /**
     * Test of SafeRequest::getQueryString() with invalid input.
     */
    function testGetQueryString_Invalid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'QUERY_STRING' => 'foo#bar'
                )
            )
        );
        $result = $req->getQueryString();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);
    }


    /**
     * Test of SafeRequest::getQueryString() with valid input.
     */
    function testGetQueryString_Valid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'QUERY_STRING' => 'foo=bar'
                )
            )
        );
        $result = $req->getQueryString();
        $this->assertIsA($result, 'string');
        $this->assertEqual('foo=bar', $result);
    }


    /**
     * Test of SafeRequest::getRemoteAddr() with null input.
     */
    function testGetRemoteAddr_Null()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                )
            )
        );
        $result = $req->getRemoteAddr();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);
    }


    /**
     * Test of SafeRequest::getRemoteAddr() with invalid input.
     */
    function testGetRemoteAddr_Invalid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'REMOTE_ADDR' => '123.456.7.89'
                )
            )
        );
        $result = $req->getRemoteAddr();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);
    }


    /**
     * Test of SafeRequest::getRemoteAddr() with valid input.
     */
    function testGetRemoteAddr_Valid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'REMOTE_ADDR' => '123.45.67.89'
                )
            )
        );
        $result = $req->getRemoteAddr();
        $this->assertIsA($result, 'string');
        $this->assertEqual('123.45.67.89', $result);
    }


    /**
     * Test of SafeRequest::getRemoteHost() with null input.
     */
    function testGetRemoteHost_Null()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                )
            )
        );
        $result = $req->getRemoteHost();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);
    }


    /**
     * Test of SafeRequest::getRemoteHost() with invalid input.
     */
    function testGetRemoteHost_Invalid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'REMOTE_HOST' => 'example%com'
                )
            )
        );
        $result = $req->getRemoteHost();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);

        $req = new SafeRequest(
            array(
                'env' => array(
                    'REMOTE_HOST' => '123.45.67.89'
                )
            )
        );
        $result = $req->getRemoteHost();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);

        $req = new SafeRequest(
            array(
                'env' => array(
                    'REMOTE_HOST' => '-example.com'
                )
            )
        );
        $result = $req->getRemoteHost();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);
    }


    /**
     * Test of SafeRequest::getRemoteHost() with valid input.
     */
    function testGetRemoteHost_Valid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'REMOTE_HOST' => 'example.com'
                )
            )
        );
        $result = $req->getRemoteHost();
        $this->assertIsA($result, 'string');
        $this->assertEqual('example.com', $result);

        $req = new SafeRequest(
            array(
                'env' => array(
                    'REMOTE_HOST' => '0example0.com'
                )
            )
        );
        $result = $req->getRemoteHost();
        $this->assertIsA($result, 'string');
        $this->assertEqual('0example0.com', $result);

        $req = new SafeRequest(
            array(
                'env' => array(
                    'REMOTE_HOST' => 'foo-bar.0example0.com'
                )
            )
        );
        $result = $req->getRemoteHost();
        $this->assertIsA($result, 'string');
        $this->assertEqual('foo-bar.0example0.com', $result);
    }


    /**
     * Test of SafeRequest::getRemoteHost() with null input.
     */
    function testGetRemoteUser_Null()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                )
            )
        );
        $result = $req->getRemoteUser();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);
    }


    /**
     * Test of SafeRequest::getRemoteUser() with invalid input.
     */
    function testGetRemoteUser_Invalid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'REMOTE_USER' => 'user:1'
                )
            )
        );
        $result = $req->getRemoteUser();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);
    }


    /**
     * Test of SafeRequest::getRemoteUser() with valid input.
     */
    function testGetRemoteUser_Valid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'REMOTE_USER' => 'user_1'
                )
            )
        );
        $result = $req->getRemoteUser();
        $this->assertIsA($result, 'string');
        $this->assertEqual('user_1', $result);
    }


    /**
     * Test of SafeRequest::getMethod() with null input.
     */
    function testGetMethod_Null()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                )
            )
        );
        $result = $req->getMethod();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);
    }


    /**
     * Test of SafeRequest::getMethod() with invalid input.
     */
    function testGetMethod_Invalid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'REQUEST_METHOD' => 'GETS'
                )
            )
        );
        $result = $req->getMethod();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);
    }


    /**
     * Test of SafeRequest::getMethod() with valid input.
     */
    function testGetMethod_Valid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'REQUEST_METHOD' => 'GET'
                )
            )
        );
        $result = $req->getMethod();
        $this->assertIsA($result, 'string');
        $this->assertEqual('GET', $result);
    }


    /**
     * Test of SafeRequest::getServerName() with null input.
     */
    function testGetServerName_Null()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                )
            )
        );
        $result = $req->getServerName();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);
    }


    /**
     * Test of SafeRequest::getServerName() with invalid input.
     */
    function testGetServerName_Invalid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'SERVER_NAME' => '0123456789012345678901234567890123456789012345678901234567890123.com'
                )
            )
        );
        $result = $req->getServerName();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);

        $req = new SafeRequest(
            array(
                'env' => array(
                    'SERVER_NAME' => '123.456.7.89'
                )
            )
        );
        $result = $req->getServerName();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);

        $req = new SafeRequest(
            array(
                'env' => array(
                    'SERVER_NAME' => 'example%com'
                )
            )
        );
        $result = $req->getServerName();
        $this->assertIsA($result, 'string');
        $this->assertEqual('', $result);
    }


    /**
     * Test of SafeRequest::getServerName() with valid input.
     */
    function testGetServerName_Valid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'SERVER_NAME' => '123.45.67.89'
                )
            )
        );
        $result = $req->getServerName();
        $this->assertIsA($result, 'string');
        $this->assertEqual('123.45.67.89', $result);

        $req = new SafeRequest(
            array(
                'env' => array(
                    'SERVER_NAME' => 'example.com'
                )
            )
        );
        $result = $req->getServerName();
        $this->assertIsA($result, 'string');
        $this->assertEqual('example.com', $result);
    }


    /**
     * Test of SafeRequest::getServerPort() with null input.
     */
    function testGetServerPort_Null()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                )
            )
        );
        $result = $req->getServerPort();
        $this->assertIsA($result, 'int');
        $this->assertEqual(0, $result);
    }


    /**
     * Test of SafeRequest::getServerPort() with invalid input.
     */
    function testGetServerPort_Invalid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'SERVER_PORT' => '65536'
                )
            )
        );
        $result = $req->getServerPort();
        $this->assertIsA($result, 'int');
        $this->assertEqual(0, $result);
    }


    /**
     * Test of SafeRequest::getServerPort() with valid input.
     */
    function testGetServerPort_Valid()
    {
        $req = new SafeRequest(
            array(
                'env' => array(
                    'SERVER_PORT' => '80'
                )
            )
        );
        $result = $req->getServerPort();
        $this->assertIsA($result, 'int');
        $this->assertEqual(80, $result);
    }


    /**
     * Test of SafeRequest::getHeader() with null input.
     */
    function testGetHeader_Null()
    {
        $req = new SafeRequest(
            array(
                'headers' => array(
                )
            )
        );
        $result = $req->getHeader('HTTP_ACCEPT');
        $this->assertIsA($result, 'null');
    }


    /**
     * Test of SafeRequest::getHeader() with invalid input.
     */
    function testGetHeader_Invalid()
    {
        $req = new SafeRequest(
            array(
                'headers' => array(
                    'HTTP_ACCEPT' => '%00text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
                )
            )
        );
        $result = $req->getHeader('HTTP_ACCEPT');
        $this->assertIsA($result, 'null');
    }


    /**
     * Test of SafeRequest::getHeader() with valid input.
     */
    function testGetHeader_Valid()
    {
        $req = new SafeRequest(
            array(
                'headers' => array(
                    'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
                )
            )
        );
        $result = $req->getHeader('HTTP_ACCEPT');
        $this->assertIsA($result, 'string');
        $this->assertEqual('text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8', $result);
    }


    /**
     * Test of SafeRequest::getCookie() with null input.
     */
    function testGetCookie_Null()
    {
        $req = new SafeRequest(
            array(
                'cookies' => array(
                )
            )
        );
        $result = $req->getCookie('foo');
        $this->assertIsA($result, 'null');
    }


    /**
     * Test of SafeRequest::getCookie() with invalid input.
     */
    function testGetCookie_Invalid()
    {
        $req = new SafeRequest(
            array(
                'cookies' => array(
                    'foo' => '\r\n\r\nGET /foo HTTP/1.1\r\nHost:example.com\r\n\r\n<html><script>alert(1)</html></script>'
                )
            )
        );
        $result = $req->getCookie('foo');
        $this->assertIsA($result, 'null');
    }


    /**
     * Test of SafeRequest::getCookie() with valid input.
     */
    function testGetCookie_Valid()
    {
        $req = new SafeRequest(
            array(
                'cookies' => array(
                    'foo' => 'bar'
                )
            )
        );
        $result = $req->getCookie('foo');
        $this->assertIsA($result, 'string');
        $this->assertEqual('bar', $result);
    }


}
