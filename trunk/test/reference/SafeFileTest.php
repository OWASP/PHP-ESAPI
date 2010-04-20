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
 * @author    Arnaud Labenne <arnaud.labenne@dotsafe.fr>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   SVN: $Id$
 * @link      http://www.owasp.org/index.php/ESAPI
 */


/**
 * Require ESAPI and SafeFile.
 */
require_once dirname(__FILE__).'/../../src/ESAPI.php';
require_once dirname(__FILE__).'/../../src/SafeFile.php';


/**
 * Unit Tests for the SafeFile extension to SplFileObject.
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @author    Arnaud Labenne <arnaud.labenne@dotsafe.fr>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   Release: @package_version@
 * @link      http://www.owasp.org/index.php/ESAPI
 */
class SafeFileTest extends UnitTestCase
{
    /**
     * Constructor ensures global ESAPI is set.
     *
     * @return null
     */
    public function __construct()
    {
        global $ESAPI;

        if (!isset($ESAPI)) {
            $ESAPI = new ESAPI(dirname(__FILE__) . '/../testresources/ESAPI.xml');
        }
    }


    /**
     * Test constructor of class SafeFile.
     *
     * @return bool True on Pass.
     */
    function testSafeFile()
    {
        $config = ESAPI::getSecurityConfiguration();
        $file = $config->getResourceDirectory() . '/ESAPI.xml';

        $sf = null;
        try {
            $sf = new SafeFile($file);
        } catch (Exception $e) {
            $this->fail('SafeFile threw an exception during construction');
        }
        if ($sf && !$sf->isReadable()) {
            $this->fail("{$file} is not readable");
        } else {
            $this->pass();
        }
    }


    /**
     * Test constructor of class SafeFile with Invalid path.
     *
     * @return bool True on Pass.
     */
    function testSafeFileWithNullByteInFileName()
    {
        $config = ESAPI::getSecurityConfiguration();
        $file = $config->getResourceDirectory() . '/ESAPI.xml' . chr(0);

        $this->expectException('EnterpriseSecurityException');
        $sf = new SafeFile($file);
    }


    /**
     * Test constructor of class SafeFile with Valid path.
     *
     * @return bool True on Pass.
     */
    function testSafeFileWithDevNull()
    {
        $file = null;
        if (substr(PHP_OS, 0, 3) == 'WIN') {
            $file = 'nul';
        } else {
            $file = '/dev/null';
        }

        $sf = new SafeFile($file);
        if (!$sf->isReadable()) {
            $this->fail("{$file} is not readable - %s");
        } else {
            $this->pass();
        }
    }


    /**
     * Test class SafeFile with Invalid path.
     * On windows, this test will bypass the protection provided by SplFileObject
     * by using a valid device name (nul) with an invalid file extension and hence
     * tests SafeFile privat _doFileCheck method.
     * On *nix, the test input will be caught by SplFileObject.
     *
     * @return bool True on Pass.
     */
    function testSafeFileWithDevNullAndPercentEncoding()
    {
        $file = null;
        if (substr(PHP_OS, 0, 3) == 'WIN') {
            $file = 'nul.%07';
            $this->expectException('ValidationException');
        } else {
            $file = '/dev/null.%07';
            $this->expectException('EnterpriseSecurityException');
        }

        $sf = new SafeFile($file);
    }


    /**
     * Test constructor of class SafeFile with Invalid path.
     *
     * @return bool True on Pass.
     */
    function testSafeFileWithNullByteInDirName()
    {
        $config = ESAPI::getSecurityConfiguration();
        $file = $config->getResourceDirectory() . chr(0) . '/ESAPI.xml';

        $this->expectException('EnterpriseSecurityException');
        $sf = new SafeFile($file);
    }


    /**
     * Test constructor of class SafeFile with Invalid path.
     *
     * @return bool True on Pass.
     */
    function testSafeFileWithPercentEncodingInFileName01()
    {
        $config = ESAPI::getSecurityConfiguration();
        $file = $config->getResourceDirectory() . '/ESAPI.xml%00';

        $this->expectException('EnterpriseSecurityException');
        $sf = new SafeFile($file);
    }


    /**
     * Test constructor of class SafeFile with Invalid path.
     *
     * @return bool True on Pass.
     */
    function testSafeFileWithPercentEncodingInFileName02()
    {
        $config = ESAPI::getSecurityConfiguration();
        $file = $config->getResourceDirectory() . '/ESAPI.xml%3C';

        $this->expectException('EnterpriseSecurityException');
        $sf = new SafeFile($file);
    }


    /**
     * Test constructor of class SafeFile with Invalid path.
     *
     * @return bool True on Pass.
     */
    function testSafeFileWithPercentEncodingInFileName03()
    {
        $config = ESAPI::getSecurityConfiguration();
        $file = $config->getResourceDirectory() . '/ESAPI.xml%3c';

        $this->expectException('EnterpriseSecurityException');
        $sf = new SafeFile($file);
    }


    /**
     * Test constructor of class SafeFile with Invalid path.
     *
     * @return bool True on Pass.
     */
    function testSafeFileWithPercentEncodingInFileName04()
    {
        $config = ESAPI::getSecurityConfiguration();
        $file = $config->getResourceDirectory() . '/ESAPI.xml%Ac';

        $this->expectException('EnterpriseSecurityException');
        $sf = new SafeFile($file);
    }


    /**
     * Test constructor of class SafeFile with Invalid path.
     *
     * @return bool True on Pass.
     */
    function testSafeFileWithPercentEncodingInFile()
    {
        $config = ESAPI::getSecurityConfiguration();
        $file = $config->getResourceDirectory() . "%00/ESAPI.xml";

        $this->expectException('EnterpriseSecurityException');
        $sf = new SafeFile($file);
    }


    /**
     * Test constructor of class SafeFile with Invalid path.
     *
     * @return bool True on Pass.
     */
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
        $this->pass();
    }


    /**
     * Test constructor of class SafeFile with Invalid path.
     *
     * @return bool True on Pass.
     */
    function testSafeFileHighByteInFileName()
    {
        $config = ESAPI::getSecurityConfiguration();
        $file = $config->getResourceDirectory() . "/ESAPI" . chr(200) . ".xml";

        $this->expectException('EnterpriseSecurityException');
        $sf = new SafeFile($file);
    }


    /**
     * Test constructor of class SafeFile with Invalid path.
     *
     * @return bool True on Pass.
     */
    function testSafeFileHighByteInDirName()
    {
        $config = ESAPI::getSecurityConfiguration();
        $file = $config->getResourceDirectory() . chr(200) . "/ESAPI.xml";

        $this->expectException('EnterpriseSecurityException');
        $sf = new SafeFile($file);
    }


    /**
     * Test constructor of class SafeFile with Invalid path.
     *
     * @return bool True on Pass.
     */
    function testSafeFileLowByteInDirName()
    {
        $config = ESAPI::getSecurityConfiguration();
        $file = $config->getResourceDirectory() . chr(8) . "/ESAPI.xml";

        $this->expectException('EnterpriseSecurityException');
        $sf = new SafeFile($file);
    }


    /**
     * Test constructor of class SafeFile with Invalid path.
     *
     * @return bool True on Pass.
     */
    function testSafeFileLowByteInFileName()
    {
        $config = ESAPI::getSecurityConfiguration();
        $file = $config->getResourceDirectory() . "/ESAPI" . chr(8) . ".xml";

        $this->expectException('EnterpriseSecurityException');
        $sf = new SafeFile($file);
    }


      /**
     * Test null byte injection.
     *
     * @return bool True on Pass.
     */
    function testURILocal()
    {
        $file = null;
        if (substr(PHP_OS, 0, 3) == 'WIN') {
            $file = 'file:///C://WINDOWS/system32/drivers/etc/hosts';
        } else {
            $file = 'file:///etc/hosts';
        }

        try{
            $sf = new SafeFile($file);
        } catch(Exception $e) {
            $this->fail(
                'This test could not run so did not really fail. Please choose a suitable test input.'
            );
        }

        $file .= chr(0) . '/test.php'; // SplFileObject doesn't catch this!

        $this->expectException('ValidationException'); // but we will!
        $sf = new SafeFile($file);
    }


      /**
     * Test null byte injection.
     *
     * @return bool True on Pass.
     */
    function testURIRemote()
    {
        $file = 'http://www.google.com/index.html';

        try{
            $sf = new SafeFile($file);
        } catch(Exception $e) {
            $this->fail(
                'This test could not run so did not really fail. Please choose a suitable test input.'
            );
        }

        $file .= chr(0);

        $this->expectException('ValidationException');
        $sf = new SafeFile($file);
    }

    /*
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
    */
}
