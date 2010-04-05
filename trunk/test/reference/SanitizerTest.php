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


require_once dirname(__FILE__).'/../../src/ESAPI.php';
require_once dirname(__FILE__).'/../../src/reference/DefaultSanitizer.php';


class SanitizerTest extends UnitTestCase
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
     * Test of getSanitizedHTML method of class Sanitizer.
     */
    function testGetSanitizedHTML_01() {
        $san = ESAPI::getSanitizer();
        
        $test1 = '<b>Jeff</b>';
        $result1 = $san->getSanitizedHTML('test', $test1, 100, false);
        $this->assertEqual($test1, $result1);
    }

    /**
     * Test of getSanitizedHTML method of class Sanitizer.
     */
    function testGetSanitizedHTML_02() {
        $san = ESAPI::getSanitizer();
        
        $test2 = "<a href=\"http://www.aspectsecurity.com\">Aspect Security</a>";
        $result2 = $san->getSanitizedHTML('test', $test2, 100, false);
        $this->assertEqual($test2, $result2);
    }

    /**
     * Test of getSanitizedHTML method of class Sanitizer.
     */
    function testGetSanitizedHTML_03() {
        $san = ESAPI::getSanitizer();
        
        $test3 = 'Test.<script>alert(document.cookie)</script>';
        $result3 = $san->getSanitizedHTML('test', $test3, 100, false);
        $this->assertEqual('Test.', $result3);
    }
    
}
