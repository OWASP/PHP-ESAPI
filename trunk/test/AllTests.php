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
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   SVN: $Id$
 * @link      http://www.owasp.org/index.php/ESAPI
 */

require_once dirname(__FILE__).'/../lib/simpletest/unit_tester.php';
require_once dirname(__FILE__).'/../lib/simpletest/reporter.php';

error_reporting(E_ALL | ~E_STRICT);  

require_once dirname(__FILE__).'/../src/ESAPI.php';

$ESAPI = new ESAPI(dirname(__FILE__)."/testresources/ESAPI.xml");

session_start(); // For HTTPUtilities

$test = new GroupTest('Core Function Tests');
    $test->addTestFile(dirname(__FILE__).'/errors/EnterpriseSecurityExceptionTest.php');
    $test->addTestFile(dirname(__FILE__).'/reference/SecurityConfigurationTest.php');
    $test->addTestFile(dirname(__FILE__).'/reference/ValidatorTest.php');
    $test->addTestFile(dirname(__FILE__).'/reference/EncoderTest.php');
    $test->addTestFile(dirname(__FILE__).'/reference/ExecutorTest.php');
    $test->addTestFile(dirname(__FILE__).'/reference/SafeFileTest.php');
$test->run(new HTMLReporter('UTF-8'));

$test = new GroupTest('Core Function Helper Tests');
    $test->addTestFile(dirname(__FILE__).'/reference/ValidationRulesTest.php');
    $test->addTestFile(dirname(__FILE__).'/codecs/Base64CodecTest.php');
    $test->addTestFile(dirname(__FILE__).'/codecs/CSSCodecTest.php');
    $test->addTestFile(dirname(__FILE__).'/codecs/HTMLEntityCodecTest.php');
    $test->addTestFile(dirname(__FILE__).'/codecs/JavaScriptCodecTest.php');
    $test->addTestFile(dirname(__FILE__).'/codecs/MySQLCodecTest.php');
    $test->addTestFile(dirname(__FILE__).'/codecs/OracleCodecTest.php');
    $test->addTestFile(dirname(__FILE__).'/codecs/PercentCodecTest.php');
    $test->addTestFile(dirname(__FILE__).'/codecs/UnixCodecTest.php');
    $test->addTestFile(dirname(__FILE__).'/codecs/VBScriptCodecTest.php');
    $test->addTestFile(dirname(__FILE__).'/codecs/WindowsCodecTest.php');
    $test->addTestFile(dirname(__FILE__).'/reference/HTTPUtilitiesTest.php');
    $test->addTestFile(dirname(__FILE__).'/filters/SafeRequestTest.php');
    $test->addTestFile(dirname(__FILE__).'/reference/RandomizerTest.php');
    $test->addTestFile(dirname(__FILE__).'/reference/StringUtilitiesTest.php');
$test->run(new HTMLReporter('UTF-8'));

$test = new GroupTest('Reference Implementation Tests');
//    $test->addTestFile(dirname(__FILE__).'/reference/AccessControllerTest.php');
    $test->addTestFile(dirname(__FILE__).'/reference/IntegerAccessReferenceMapTest.php');
    $test->addTestFile(dirname(__FILE__).'/reference/RandomAccessReferenceMapTest.php');
    $test->addTestFile(dirname(__FILE__).'/reference/AuditorTest.php');
//    $test->addTestFile(dirname(__FILE__).'/reference/IntrusionDetectorTest.php');
    $test->addTestFile(dirname(__FILE__).'/reference/SanitizerTest.php');
$test->run(new HTMLReporter('UTF-8'));

?>