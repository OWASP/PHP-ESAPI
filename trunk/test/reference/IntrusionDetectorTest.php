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
 * Require Test Helpers
 */
require_once dirname(__FILE__) . '/../testresources/TestHelpers.php';


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
    
    function __construct()
    {
        ESAPI::getEncoder();
        $this->logFileLoc = getLogFileLoc();
    }

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
        if ($this->logFileLoc === false) {
            $this->fail(
                'Cannot perform this test because the log file cannot be found.'
            );
        }

        $logMsg = 'testExceptionAutoAdd:';
        $logMsg .= getRandomAlphaNumString(32);
        new EnterpriseSecurityException(
            'user message: testExceptionAutoAdd', $logMsg
        );

        $m = 'Test attempts to detect exception log message in logfile: %s';
        $this->assertTrue(
            fileContainsExpected($this->logFileLoc, $logMsg),
            $m
        );
    }


    /**
     * Test of addException method of class DefaultIntrusionDetector.
     *
     */
    function testAddException()
    {
        if ($this->logFileLoc === false) {
            $this->fail(
                'Cannot perform this test because the log file cannot be found.'
            );
        }

        $logMsg = 'testAddException:';
        $logMsg .= getRandomAlphaNumString(32);
        ESAPI::getIntrusionDetector()->addException(new Exception($logMsg));

        $m = 'Test attempts to detect exception log message in logfile: %s';
        $this->assertTrue(
            fileContainsExpected($this->logFileLoc, $logMsg),
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
        if ($this->logFileLoc === false) {
            $this->fail(
                'Cannot perform this test because the log file cannot be found.'
            );
        }

        // generate a random event name
        $eventName = getRandomAlphaNumString(16);

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
        
        array_pop($secConfig->events);

        $find = "User exceeded quota of {$threshold->count} " .
            "per {$threshold->interval} seconds for event " .
            "{$eventName}. Taking actions \[" .
            implode(', ', $threshold->actions) . '\]';
        $m = 'Test attempts to detect IntrusionDetector' .
            ' action log message in logfile: %s';
        $this->assertTrue(
            fileContainsExpected($this->logFileLoc, $find),
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
        if ($this->logFileLoc === false) {
            $this->fail(
                'Cannot perform this test because the log file cannot be found.'
            );
        }

        $eventName = 'IntrusionException';
        
        // modify the threshold for this event so that it can be reliably
        // detected in the log file.
        require_once dirname(__FILE__) . '/../../src/SecurityConfiguration.php';
        $dummyAction = 'TEST' . getRandomAlphaNumString(16);
        $moddedThreshold = new Threshold(
            $eventName, 1, 1, array('log', $dummyAction)
        );
        $secConfig = ESAPI::getSecurityConfiguration();
        $secConfig->getQuota('1'); // make sure events are loaded
        $ieKey = null;
        $restoreThreshold = null;
        foreach ($secConfig->events as $key => $threshold) {
            if ($threshold->name == $eventName) {
                $ieKey = $key;
                $restoreThreshold = $threshold;
                break;
            }
        }
        if ($ieKey !== null) {
            $secConfig->events[$ieKey] = $moddedThreshold;
        } else {
            $secConfig->events[] = $moddedThreshold;
        }
        
        ESAPI::getIntrusionDetector()->addException(
            new IntrusionException(
                'Naughty User!',
                'testAddIntrusionExceptionIsTracked'
            )
        );

        // restore the threshold actions
        if ($ieKey !== null) {
            $secConfig->events[$ieKey] = $restoreThreshold;
        } else {
            array_pop($secConfig->events);
        }
        

        $find = "User exceeded quota of {$moddedThreshold->count} " .
            "per {$moddedThreshold->interval} seconds for event " .
            "{$eventName}. Taking actions \[" .
            implode(', ', $moddedThreshold->actions) . '\]';
        $m = 'Test attempts to detect IntrusionDetector' .
            ' action log message in logfile: %s';
        $this->assertTrue(
            fileContainsExpected($this->logFileLoc, $find),
            $m
        );
    }


    /**
     * Test Rapid events
     *
     */
    function testRapidIDSEvents()
    {
        if ($this->logFileLoc === false) {
            $this->fail(
                'Cannot perform this test because the log file cannot be found.'
            );
        }

        $eventName = 'RapidEvent';
        
        // make a new threshold with a dummy action that can be detected in the
        // logfile
        require_once dirname(__FILE__) . '/../../src/SecurityConfiguration.php';
        $dummyAction = 'TEST' . getRandomAlphaNumString(16);
        $moddedThreshold = new Threshold(
            $eventName, 10, 20, array('log', $dummyAction)
        );
        $secConfig = ESAPI::getSecurityConfiguration();
        $secConfig->getQuota('1'); // make sure events are loaded
        $ieKey = null;
        $restoreThreshold = null;
        foreach ($secConfig->events as $key => $threshold) {
            if ($threshold->name == $eventName) {
                $ieKey = $key;
                $restoreThreshold = $threshold;
                break;
            }
        }
        if ($ieKey !== null) {
            $secConfig->events[$ieKey] = $moddedThreshold;
        } else {
            $secConfig->events[] = $moddedThreshold;
        }     

        // Generate Exceptions
        $ids = ESAPI::getIntrusionDetector();
        for ($i = 1; $i < 11; $i++) {
            $ids->addEvent(
                $eventName,
                'This is a Test Event for IntrusionDetectorTest.'
            );
        }
        
        // Cleanup - remove the test threshold from secConfig
        if ($ieKey !== null) {
            $secConfig->events[$ieKey] = $restoreThreshold;
        } else {
            array_pop($secConfig->events);
        }
        

        $find = "User exceeded quota of {$moddedThreshold->count} " .
            "per {$moddedThreshold->interval} seconds for event " .
            "{$eventName}. Taking actions \[" .
            implode(', ', $moddedThreshold->actions) . '\]';
        $m = 'Test attempts to detect IntrusionDetector' .
            ' action log message in logfile: %s';
        $this->assertTrue(
            fileContainsExpected($this->logFileLoc, $find),
            $m
        );
    }


    /**
     * Once IntrusionDetector has been triggered, it can be triggered again with
     * another occurrence of the same event
     *
     */
    function testTripTwice()
    {
        if ($this->logFileLoc === false) {
            $this->fail(
                'Cannot perform this test because the log file cannot be found.'
            );
        }

        $eventName = 'RapidEvent';
        
        // make a new threshold with a dummy action that can be detected in the
        // logfile
        require_once dirname(__FILE__) . '/../../src/SecurityConfiguration.php';
        $dummyAction = 'TEST' . getRandomAlphaNumString(16);
        $moddedThreshold = new Threshold(
            $eventName, 10, 20, array('log', $dummyAction)
        );
        $secConfig = ESAPI::getSecurityConfiguration();
        $secConfig->getQuota('1'); // make sure events are loaded
        $ieKey = null;
        $restoreThreshold = null;
        foreach ($secConfig->events as $key => $threshold) {
            if ($threshold->name == $eventName) {
                $ieKey = $key;
                $restoreThreshold = $threshold;
                break;
            }
        }
        if ($ieKey !== null) {
            $secConfig->events[$ieKey] = $moddedThreshold;
        } else {
            $secConfig->events[] = $moddedThreshold;
        }     
        
        // Note that the previous test testRapidValidationErrors has triggered
        // IDS for this event so we only need one more event to trigger again.
        ESAPI::getIntrusionDetector()->addEvent(
            $eventName,
            'This is a Test Event for IntrusionDetectorTest.'
        );
        
        // Cleanup - remove the test threshold from secConfig
        if ($ieKey !== null) {
            $secConfig->events[$ieKey] = $restoreThreshold;
        } else {
            array_pop($secConfig->events);
        }
        

        $find = "User exceeded quota of {$moddedThreshold->count} " .
            "per {$moddedThreshold->interval} seconds for event " .
            "{$eventName}. Taking actions \[" .
            implode(', ', $moddedThreshold->actions) . '\]';
        $m = 'Test attempts to detect IntrusionDetector' .
            ' action log message in logfile: %s';
        $this->assertTrue(
            fileContainsExpected($this->logFileLoc, $find),
            $m
        );
    }


    /**
     * This test will trigger IDS at a point which demonstrates the calculation
     * of event intervals.  Using a threshold that triggers after 5 events
     * within 5 seconds, four events will occur at 1 second intervals, then a
     * pause of 3 seconds and then 3 more events in quick succession.  IDS
     * should not trigger until the 7th event.
     * 
     *                                   *
     *         e   e   e   e           eee
     *         |-+-|-+-|-+-|-+-|-+-|-+-|-+-|-+-|
     *         0   1   2   3   4   5   6   7   8
     *                 |___________________|
     *                   5 second interval
     */
    function testSlidingInterval()
    {
        if ($this->logFileLoc === false) {
            $this->fail(
                'Cannot perform this test because the log file cannot be found.'
            );
        }

        $eventName = 'SlidingIntervalTestEvent';
        
        // make a new threshold with a dummy action that can be detected in the
        // logfile
        require_once dirname(__FILE__) . '/../../src/SecurityConfiguration.php';
        $dummyAction = 'TEST' . getRandomAlphaNumString(16);
        $moddedThreshold = new Threshold(
            $eventName, 5, 5, array('log', $dummyAction)
        );
        $find = "User exceeded quota of {$moddedThreshold->count} " .
            "per {$moddedThreshold->interval} seconds for event " .
            "{$eventName}. Taking actions \[" .
            implode(', ', $moddedThreshold->actions) . '\]';
        $m = 'Test attempts to detect IntrusionDetector' .
            ' action log message in logfile: %s';
        
        $secConfig = ESAPI::getSecurityConfiguration();
        $secConfig->getQuota('1'); // make sure events are loaded
        $ieKey = null;
        $restoreThreshold = null;
        foreach ($secConfig->events as $key => $threshold) {
            if ($threshold->name == $eventName) {
                $ieKey = $key;
                $restoreThreshold = $threshold;
                break;
            }
        }
        if ($ieKey !== null) {
            $secConfig->events[$ieKey] = $moddedThreshold;
        } else {
            $secConfig->events[] = $moddedThreshold;
        }     
        
        // Generate 4 events at 1 sec intervals
        for ($i = 0; $i < 4; $i++) {
            ESAPI::getIntrusionDetector()->addEvent(
                $eventName,
                'This is a Test Event for IntrusionDetectorTest.'
            );
            usleep(1000000);
        }
        // Sleep for a further 2 secs (for a total of 3 secs between this and
        // the next event.
        usleep(2000000);
        
        // The following two events should not trigger...
        ESAPI::getIntrusionDetector()->addEvent(
            $eventName,
            'This is a Test Event for IntrusionDetectorTest.'
        );

        $this->assertFalse(
            fileContainsExpected($this->logFileLoc, $find),
            $m
        );
        
        ESAPI::getIntrusionDetector()->addEvent(
            $eventName,
            'This is a Test Event for IntrusionDetectorTest.'
        );
        $this->assertFalse(
            fileContainsExpected($this->logFileLoc, $find),
            $m
        );
        
        // OK this event SHOULD trigger!
        ESAPI::getIntrusionDetector()->addEvent(
            $eventName,
            'This is a Test Event for IntrusionDetectorTest.'
        );
        $this->assertTrue(
            fileContainsExpected($this->logFileLoc, $find),
            $m
        );
        
        
        // Cleanup - remove the test threshold from secConfig
        if ($ieKey !== null) {
            $secConfig->events[$ieKey] = $restoreThreshold;
        } else {
            array_pop($secConfig->events);
        }
    }
}
