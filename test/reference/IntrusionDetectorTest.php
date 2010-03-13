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
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */


/**
 * Test for the DefaultIntrusionDetector implementation of the IntrusionDetector
 * interface.
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
class IntrusionDetectorTest extends UnitTestCase
{

    private $logFileLoc = null;
    private $charset = null;
    private $rnd = null;

    function setUp()
    {
        global $ESAPI;
        if (!isset($ESAPI))
        {
            $ESAPI = new ESAPI(dirname(__FILE__).'/../testresources/ESAPI.xml');
        }
    }

    function tearDown() {}

    /**
     * Test to ensure that EnterpriseSecurityExceptions are automatically added
     * to the IntrusionDetector and that the IntrusionDetector logs the
     * exceptions logMessage.
     *
     */
    function testExceptionAutoAdd()
    {
        $this->getLogFileLoc();
        if ($this->logFileLoc === false) {
            $this->fail(
                'Cannot perform this test because the log file cannot be found.'
            );
        }

        $logMsg = 'testExceptionAutoAdd:';
        $logMsg .= $this->getRandomAlphaNumString(32);
        new EnterpriseSecurityException(
            'user message: testExceptionAutoAdd', $logMsg
        );

        $m = 'Test attempts to detect exception log message in logfile: %s';
        $this->assertTrue(
            $this->fileContainsExpected($this->logFileLoc, $logMsg),
            $m
        );
    }


    /**
     * Test of addException method of class DefaultIntrusionDetector.
     *
     */
    function testAddException()
    {
        $this->getLogFileLoc();
        if ($this->logFileLoc === false) {
            $this->fail(
                'Cannot perform this test because the log file cannot be found.'
            );
        }

        $logMsg = 'testAddException:';
        $logMsg .= $this->getRandomAlphaNumString(32);
        ESAPI::getIntrusionDetector()->addException(new Exception($logMsg));

        $m = 'Test attempts to detect exception log message in logfile: %s';
        $this->assertTrue(
            $this->fileContainsExpected($this->logFileLoc, $logMsg),
            $m
        );
    }


    /**
     * Test of addEvent method of DefaultIntrusionDetector.  This test checks
     * that a threshold exceeded message is logged and thus tests the addEvent,
     * addSecurityEvent and Event.increment methods and that takeSecurityAction
     * performs the 'log' action.
     *
     */
    function testAddEvent()
    {
        // generate a random event name
        $eventName = $this->getRandomAlphaNumString(16);

        // create a threshold for this event so that it will be tracked by IDS
        require_once dirname(__FILE__) . '/../../src/SecurityConfiguration.php';
        $threshold = new Threshold($eventName, 1, 60, array('log'));
        $secConfig = ESAPI::getSecurityConfiguration();
        $secConfig->getQuota('1'); // make sure events are loaded
        $secConfig->events[] = $threshold;

        // add event
        ESAPI::getIntrusionDetector()->addEvent(
            $eventName,
            'This is a Test Event for IntrusionDetectorTest.'
        );

        $find = 'User exceeded quota of 1 per 60 seconds for event ' .
            $eventName . '. Taking actions \[log\]';
        $m = 'Test attempts to detect IntrusionDetector' .
            ' action log message in logfile: %s';
        $this->assertTrue(
            $this->fileContainsExpected($this->logFileLoc, $find),
            $m
        );
    }


    /**
     * This test shows that IntrusionExceptions can be tracked by
     * IntrusionDetector.
     *
     */
    function testAddIntrusionExceptionIsTracked()
    {
        $this->getLogFileLoc();
        if ($this->logFileLoc === false) {
            $this->fail(
                'Cannot perform this test because the log file cannot be found.'
            );
        }

        // modify the threshold for this event so that it can be reliably
        // detected in the log file.
        require_once dirname(__FILE__) . '/../../src/SecurityConfiguration.php';
        $dummyAction = 'TEST' . $this->getRandomAlphaNumString(16);
        $moddedThreshold = new Threshold(
            'IntrusionException', 1, 1, array('log', $dummyAction)
        );
        $secConfig = ESAPI::getSecurityConfiguration();
        $secConfig->getQuota('1'); // make sure events are loaded
        $ieKey = null;
        $restoreThreshold = null;
        foreach ($secConfig->events as $key => $threshold) {
            if ($threshold->name == 'IntrusionException') {
                $ieKey = $key;
                $restoreThreshold = $threshold;
                $secConfig->events[$key] = $moddedThreshold;
                break;
            }
        }

        ESAPI::getIntrusionDetector()->addException(
            new IntrusionException(
                'Naughty User!',
                'testAddIntrusionExceptionIsTracked'
            )
        );

        // restore the threshold actions
        $secConfig->events[$ieKey] = $restoreThreshold;
        

        $find = 'User exceeded quota of 1 per 1 seconds for event ' .
            "IntrusionException. Taking actions \[log, {$dummyAction}\]";
        $m = 'Test attempts to detect IntrusionDetector' .
            ' action log message in logfile: %s';
        $this->assertTrue(
            $this->fileContainsExpected($this->logFileLoc, $find),
            $m
        );
    }


    /**
     * Helper method which opens a file handle to the supplied path, reads it
     * line-by-line and performs preg_match on the line with the supplied regex.
     *
     * @param  $filename string path to file.
     * @param  $expected string regex to match in the file
     *
     * @return true if the regex matches, false if not, null if the file
     *         cannot be opened.
     */
    private function fileContainsExpected($filename, $expected)
    {
        if (empty($filename) || ! is_string($filename)) {
            return null;
        }
        $f = fopen($filename, 'r');
        if ($f === false) {
            return null;
        }
        while (! feof($f)) {
            $line = fgets($f, 2048);
            if (preg_match("/{$expected}/", $line)) {
                fclose($f);
                return true;
            }
        }
        fclose($f);
        return false;
    }


    /**
     * Helper method sets $this->logFileLoc with the ESAPILogger log file path.
     */
    private function getLogFileLoc()
    {
        if ($this->logFileLoc !== null) {
            return;
        }
        $filename = ESAPI::getSecurityConfiguration()->getLogFileName();
        $this->logFileLoc = realpath($filename);
    }


    /**
     * Helper method returns a random string of alphanumeric characters of the
     * supplied length.
     *
     * @param  $len integer length of the required string.
     *
     * @return string of $len alphanumeric characters.
     */
    private function getRandomAlphaNumString($len)
    {
        if ($this->charset === null) {
            ESAPI::getEncoder();
            $this->charset = Encoder::CHAR_ALPHANUMERICS;
        }
        if ($this->rnd === null) {
            $this->rnd = ESAPI::getRandomizer();
        }
        if (empty($len)) {
            return null;
        }
        return $this->rnd->getRandomString($len, $this->charset);
    }
}
