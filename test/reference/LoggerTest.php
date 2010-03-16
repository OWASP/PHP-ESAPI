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
 * The ESAPI is published by OWASP under the BSD license. You should read and
 * accept the LICENSE before you use, modify, and/or redistribute this software.
 *
 * @author Andrew van der Stock (vanderaj @ owasp.org)
 * @since  2009
 * @since  1.6
 */


/**
 *
 */
require_once dirname(__FILE__) . '/../testresources/TestHelpers.php';
require_once dirname(__FILE__) . '/../../src/ESAPI.php';
require_once dirname(__FILE__) . '/../../src/errors/ValidationException.php';


/**
 * This test case covers logging functioanlity.
 *
 * It verifies that the various types of log entry are logged to file as well as
 * testing DefaultLogger methods.
 *
 * @author Laura D. Bell
 * @author jah (at jahboite.co.uk)
 * @since 1.6
 */
class LoggerTest extends UnitTestCase {

    private $testCount = 0;
    private $testLogger= null;
    private $alphanum = null;
    private $rnd = null;
    private $logFileLoc = null;
    /**
     * Set the first time we attempt to read the logfile.  Used to differentiate
     * between failure to read the logfile and failure to match a pattern in the
     * logfile.
     *
     * @var boolean
     */
    private $logfileIsReadable = false;
    
    function __construct()
    {
        ESAPI::getEncoder();
        $this->logFileLoc = getLogFileLoc();
    }

    function setUp() {
        global $ESAPI;

        if ( !isset($ESAPI)) {
            $ESAPI = new
            ESAPI(dirname(__FILE__).'/../testresources/ESAPI.xml');
        }

        $this->testLogger = ESAPI::getLogger(
            'LoggerTest #' . $this->testCount++
        );
    }

    function tearDown() {
        $this->testLogger = null; // TODO - working?
    }


    function testSetLevelOffCheckTrace() {
        $this->testLogger->setLevel(ESAPILogger::OFF);
        $this->assertFalse($this->testLogger->isTraceEnabled());
    }

    function testSetLevelOffCheckDebug() {
        $this->testLogger->setLevel(ESAPILogger::OFF);
        $this->assertFalse($this->testLogger->isDebugEnabled());
    }

    function testSetLevelOffCheckInfo() {
        $this->testLogger->setLevel(ESAPILogger::OFF);
        $this->assertFalse($this->testLogger->isInfoEnabled());
    }

    function testSetLevelOffCheckWarning() {
        $this->testLogger->setLevel(ESAPILogger::OFF);
        $this->assertFalse($this->testLogger->isWarningEnabled());
    }

    function testSetLevelOffCheckError() {
        $this->testLogger->setLevel(ESAPILogger::OFF);
        $this->assertFalse($this->testLogger->isErrorEnabled());
    }

    function testSetLevelOffCheckFatal() {
        $this->testLogger->setLevel(ESAPILogger::OFF);
        $this->assertFalse($this->testLogger->isFatalEnabled());
    }


    function testSetLevelTraceCheckTrace() {
        $this->testLogger->setLevel(ESAPILogger::TRACE);
        $this->assertTrue($this->testLogger->isTraceEnabled());
    }

    function testSetLevelTraceCheckDebug() {
        $this->testLogger->setLevel(ESAPILogger::TRACE);
        $this->assertTrue($this->testLogger->isTraceEnabled());
    }

    function testSetLevelTraceCheckInfo() {
        $this->testLogger->setLevel(ESAPILogger::TRACE);
        $this->assertTrue($this->testLogger->isInfoEnabled());
    }

    function testSetLevelTraceCheckWarning() {
        $this->testLogger->setLevel(ESAPILogger::TRACE);
        $this->assertTrue($this->testLogger->isWarningEnabled());
    }

    function testSetLevelTraceCheckError() {
        $this->testLogger->setLevel(ESAPILogger::TRACE);
        $this->assertTrue($this->testLogger->isErrorEnabled());
    }

    function testSetLevelTraceCheckFatal() {
        $this->testLogger->setLevel(ESAPILogger::TRACE);
        $this->assertTrue($this->testLogger->isFatalEnabled());
    }


    function testSetLevelDebugCheckTrace() {
        $this->testLogger->setLevel(ESAPILogger::DEBUG);
        $this->assertFalse($this->testLogger->isTraceEnabled());
    }

    function testSetLevelDebugCheckDebug() {
        $this->testLogger->setLevel(ESAPILogger::DEBUG);
        $this->assertTrue($this->testLogger->isDebugEnabled());
    }

    function testSetLevelDebugCheckInfo() {
        $this->testLogger->setLevel(ESAPILogger::DEBUG);
        $this->assertTrue($this->testLogger->isInfoEnabled());
    }

    function testSetLevelDebugCheckWarning() {
        $this->testLogger->setLevel(ESAPILogger::DEBUG);
        $this->assertTrue($this->testLogger->isWarningEnabled());
    }

    function testSetLevelDebugCheckError() {
        $this->testLogger->setLevel(ESAPILogger::DEBUG);
        $this->assertTrue($this->testLogger->isErrorEnabled());
    }

    function testSetLevelDebugCheckFatal() {
        $this->testLogger->setLevel(ESAPILogger::DEBUG);
        $this->assertTrue($this->testLogger->isFatalEnabled());
    }


    function testSetLevelInfoCheckTrace() {
        $this->testLogger->setLevel(ESAPILogger::INFO);
        $this->assertFalse($this->testLogger->isTraceEnabled());
    }

    function testSetLevelInfoCheckDebug() {
        $this->testLogger->setLevel(ESAPILogger::INFO);
        $this->assertFalse($this->testLogger->isDebugEnabled());
    }

    function testSetLevelInfoCheckInfo() {
        $this->testLogger->setLevel(ESAPILogger::INFO);
        $this->assertTrue($this->testLogger->isInfoEnabled());
    }

    function testSetLevelInfoCheckWarning() {
        $this->testLogger->setLevel(ESAPILogger::INFO);
        $this->assertTrue($this->testLogger->isWarningEnabled());
    }

    function testSetLevelInfoCheckError() {
        $this->testLogger->setLevel(ESAPILogger::INFO);
        $this->assertTrue($this->testLogger->isErrorEnabled());
    }

    function testSetLevelInfoCheckFatal() {
        $this->testLogger->setLevel(ESAPILogger::INFO);
        $this->assertTrue($this->testLogger->isFatalEnabled());
    }


    function testSetLevelWarningCheckTrace() {
        $this->testLogger->setLevel(ESAPILogger::WARNING);
        $this->assertFalse($this->testLogger->isTraceEnabled());
    }

    function testSetLevelWarningCheckDebug() {
        $this->testLogger->setLevel(ESAPILogger::WARNING);
        $this->assertFalse($this->testLogger->isDebugEnabled());
    }

    function testSetLevelWarningCheckInfo() {
        $this->testLogger->setLevel(ESAPILogger::WARNING);
        $this->assertFalse($this->testLogger->isInfoEnabled());
    }

    function testSetLevelWarningCheckWarning() {
        $this->testLogger->setLevel(ESAPILogger::WARNING);
        $this->assertTrue($this->testLogger->isWarningEnabled());
    }

    function testSetLevelWarningCheckError() {
        $this->testLogger->setLevel(ESAPILogger::WARNING);
        $this->assertTrue($this->testLogger->isErrorEnabled());
    }

    function testSetLevelWarningCheckFatal() {
        $this->testLogger->setLevel(ESAPILogger::WARNING);
        $this->assertTrue($this->testLogger->isFatalEnabled());
    }


    function testSetLevelErrorCheckTrace() {
        $this->testLogger->setLevel(ESAPILogger::ERROR);
        $this->assertFalse($this->testLogger->isTraceEnabled());
    }

    function testSetLevelErrorCheckDebug() {
        $this->testLogger->setLevel(ESAPILogger::ERROR);
        $this->assertFalse($this->testLogger->isDebugEnabled());
    }

    function testSetLevelErrorCheckInfo() {
        $this->testLogger->setLevel(ESAPILogger::ERROR);
        $this->assertFalse($this->testLogger->isInfoEnabled());
    }

    function testSetLevelErrorCheckWarning() {
        $this->testLogger->setLevel(ESAPILogger::ERROR);
        $this->assertFalse($this->testLogger->isWarningEnabled());
    }

    function testSetLevelErrorCheckError() {
        $this->testLogger->setLevel(ESAPILogger::ERROR);
        $this->assertTrue($this->testLogger->isErrorEnabled());
    }

    function testSetLevelErrorCheckFatal() {
        $this->testLogger->setLevel(ESAPILogger::ERROR);
        $this->assertTrue($this->testLogger->isFatalEnabled());
    }


    function testSetLevelFatalCheckTrace() {
        $this->testLogger->setLevel(ESAPILogger::FATAL);
        $this->assertFalse($this->testLogger->isTraceEnabled());
    }

    function testSetLevelFatalCheckDebug() {
        $this->testLogger->setLevel(ESAPILogger::FATAL);
        $this->assertFalse($this->testLogger->isDebugEnabled());
    }

    function testSetLevelFatalCheckInfo() {
        $this->testLogger->setLevel(ESAPILogger::FATAL);
        $this->assertFalse($this->testLogger->isInfoEnabled());
    }

    function testSetLevelFatalCheckWarning() {
        $this->testLogger->setLevel(ESAPILogger::FATAL);
        $this->assertFalse($this->testLogger->isWarningEnabled());
    }

    function testSetLevelFatalCheckError() {
        $this->testLogger->setLevel(ESAPILogger::FATAL);
        $this->assertFalse($this->testLogger->isErrorEnabled());
    }

    function testSetLevelFatalCheckFatal() {
        $this->testLogger->setLevel(ESAPILogger::FATAL);
        $this->assertTrue($this->testLogger->isFatalEnabled());
    }


    function testSetLevelMultipleLogsExpectedTrue() {
        //Now test to see if a change to the logging level in one log affects other logs
        $newLogger = ESAPI::getLogger( 'test_num2' );
        $this->testLogger->setLevel( ESAPILogger::OFF );
        $newLogger->setLevel( ESAPILogger::INFO );
        $log_1_result = $this->testLogger->isInfoEnabled();
        $log_2_result = $newLogger->isInfoEnabled();

        if( !$log_1_result &&$log_2_result) {
            $this->pass();
        }
    }

    function testSetLevelMultipleLogsExpectedFalse() {
        //Now test to see if a change to the logging level in one log affects other logs
        $newLogger = ESAPI::getLogger( 'test_num2' );
        $this->testLogger->setLevel( ESAPILogger::OFF );
        $newLogger->setLevel( ESAPILogger::INFO );
        $log_1_result = $this->testLogger->isInfoEnabled();
        $log_2_result = $newLogger->isInfoEnabled();

        if( $log_1_result &&!$log_2_result) {
            $this->pass();
        }
    }


    function testLoggingToFile() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Test message. {$r}";
        $this->testLogger->fatal(ESAPILogger::SECURITY, true, $logMsg);
        $this->logfileIsReadable = $this->verifyLogEntry("{$logMsg}", $testMsg);
        $this->assertTrue($this->logfileIsReadable, $testMsg);
    }


    function testFatalSecuritySuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Fatal level test message. {$r}";
        $expected = $this->getExpected('FATAL', 'SECURITY', true, $logMsg);
        $this->testLogger->fatal(ESAPILogger::SECURITY, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testFatalSecurityFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Fatal level test message. {$r}";
        $expected = $this->getExpected('FATAL', 'SECURITY', false, $logMsg);
        $this->testLogger->fatal(ESAPILogger::SECURITY, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testFatalNullException() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Fatal level test message. {$r}";
        $expected = $this->getExpected('FATAL', 'SECURITY', true, $logMsg);
        $this->testLogger->fatal(ESAPILogger::SECURITY, true, $logMsg, null);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testFatalWithException() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Fatal level test message. {$r}";
        $throwable = new Exception('This is a message from a generic exception.');
        $expected = $this->getExpected('FATAL', 'SECURITY', false, $logMsg,
            get_class($throwable)
        );
        $this->testLogger->fatal(ESAPILogger::SECURITY, false, $logMsg, $throwable);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testWarningSecuritySuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Warning level test message. {$r}";
        $expected = $this->getExpected('WARNING', 'SECURITY', true, $logMsg);
        $this->testLogger->warning(ESAPILogger::SECURITY, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testWarningSecurityFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Warning level test message. {$r}";
        $expected = $this->getExpected('WARNING', 'SECURITY', false, $logMsg);
        $this->testLogger->warning(ESAPILogger::SECURITY, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testWarningNullException() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Warning level test message. {$r}";
        $expected = $this->getExpected('WARNING', 'SECURITY', true, $logMsg);
        $this->testLogger->warning(ESAPILogger::SECURITY, true, $logMsg, null);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testWarningWithException() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Warning level test message. {$r}";
        $throwable = new ValidationException('This is a message from a ValidationException.');
        $expected = $this->getExpected('WARNING', 'SECURITY', false, $logMsg,
            get_class($throwable)
        );
        $this->testLogger->warning(ESAPILogger::SECURITY, false, $logMsg, $throwable);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testErrorSecuritySuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Error level test message. {$r}";
        $expected = $this->getExpected('ERROR', 'SECURITY', true, $logMsg);
        $this->testLogger->error(ESAPILogger::SECURITY, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testErrorSecurityFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Error level test message. {$r}";
        $expected = $this->getExpected('ERROR', 'SECURITY', false, $logMsg);
        $this->testLogger->error(ESAPILogger::SECURITY, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testErrorNullException() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Error level test message. {$r}";
        $expected = $this->getExpected('ERROR', 'SECURITY', true, $logMsg);
        $this->testLogger->error(ESAPILogger::SECURITY, true, $logMsg, null);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testErrorWithException() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Error level test message. {$r}";
        $throwable = new Exception('This is a message from a generic exception.');
        $expected = $this->getExpected('ERROR', 'SECURITY', false, $logMsg,
            get_class($throwable)
        );
        $this->testLogger->error(ESAPILogger::SECURITY, false, $logMsg, $throwable);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testInfoSecuritySuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Info level test message. {$r}";
        $expected = $this->getExpected('INFO', 'SECURITY', true, $logMsg);
        $this->testLogger->info(ESAPILogger::SECURITY, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testInfoSecurityFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Info level test message. {$r}";
        $expected = $this->getExpected('INFO', 'SECURITY', false, $logMsg);
        $this->testLogger->info(ESAPILogger::SECURITY, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testInfoNullException() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Info level test message. {$r}";
        $expected = $this->getExpected('INFO', 'SECURITY', true, $logMsg);
        $this->testLogger->info(ESAPILogger::SECURITY, true, $logMsg, null);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testInfoWithException() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Info level test message. {$r}";
        $throwable = new Exception('This is a message from a generic exception.');
        $expected = $this->getExpected('INFO', 'SECURITY', false, $logMsg,
            get_class($throwable)
        );
        $this->testLogger->info(ESAPILogger::SECURITY, false, $logMsg, $throwable);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testDebugSecuritySuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Debug level test message. {$r}";
        $expected = $this->getExpected('DEBUG', 'SECURITY', true, $logMsg);
        $this->testLogger->debug(ESAPILogger::SECURITY, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testDebugSecurityFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Debug level test message. {$r}";
        $expected = $this->getExpected('DEBUG', 'SECURITY', false, $logMsg);
        $this->testLogger->debug(ESAPILogger::SECURITY, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testDebugNullException() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Debug level test message. {$r}";
        $expected = $this->getExpected('DEBUG', 'SECURITY', true, $logMsg);
        $this->testLogger->debug(ESAPILogger::SECURITY, true, $logMsg, null);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testDebugWithException() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Debug level test message. {$r}";
        $throwable = new Exception('This is a message from a generic exception.');
        $expected = $this->getExpected('DEBUG', 'SECURITY', false, $logMsg,
            get_class($throwable)
        );
        $this->testLogger->debug(ESAPILogger::SECURITY, false, $logMsg, $throwable);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testTraceSecuritySuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Trace level test message. {$r}";
        $expected = $this->getExpected('TRACE', 'SECURITY', true, $logMsg);
        $this->testLogger->trace(ESAPILogger::SECURITY, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testTraceSecurityFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Trace level test message. {$r}";
        $expected = $this->getExpected('TRACE', 'SECURITY', false, $logMsg);
        $this->testLogger->trace(ESAPILogger::SECURITY, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testTraceNullException() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Trace level test message. {$r}";
        $expected = $this->getExpected('TRACE', 'SECURITY', true, $logMsg);
        $this->testLogger->trace(ESAPILogger::SECURITY, true, $logMsg, null);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testTraceWithException() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Trace level test message. {$r}";
        $throwable = new Exception('This is a message from a generic exception.');
        $expected = $this->getExpected('TRACE', 'SECURITY', false, $logMsg,
            get_class($throwable)
        );
        $this->testLogger->trace(ESAPILogger::SECURITY, false, $logMsg, $throwable);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testFatalUsabilitySuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Fatal level test message. {$r}";
        $expected = $this->getExpected('FATAL', 'USABILITY', true, $logMsg);
        $this->testLogger->fatal(ESAPILogger::USABILITY, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testFatalUsabilityFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Fatal level test message. {$r}";
        $expected = $this->getExpected('FATAL', 'USABILITY', false, $logMsg);
        $this->testLogger->fatal(ESAPILogger::USABILITY, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testWarningUsabilitySuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Warning level test message. {$r}";
        $expected = $this->getExpected('WARNING', 'USABILITY', true, $logMsg);
        $this->testLogger->warning(ESAPILogger::USABILITY, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testWarningUsabilityFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Warning level test message. {$r}";
        $expected = $this->getExpected('WARNING', 'USABILITY', false, $logMsg);
        $this->testLogger->warning(ESAPILogger::USABILITY, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testErrorUsabilitySuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Error level test message. {$r}";
        $expected = $this->getExpected('ERROR', 'USABILITY', true, $logMsg);
        $this->testLogger->error(ESAPILogger::USABILITY, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testErrorUsabilityFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Error level test message. {$r}";
        $expected = $this->getExpected('ERROR', 'USABILITY', false, $logMsg);
        $this->testLogger->error(ESAPILogger::USABILITY, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testInfoUsabilitySuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Info level test message. {$r}";
        $expected = $this->getExpected('INFO', 'USABILITY', true, $logMsg);
        $this->testLogger->info(ESAPILogger::USABILITY, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testInfoUsabilityFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Info level test message. {$r}";
        $expected = $this->getExpected('INFO', 'USABILITY', false, $logMsg);
        $this->testLogger->info(ESAPILogger::USABILITY, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testDebugUsabilitySuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Debug level test message. {$r}";
        $expected = $this->getExpected('DEBUG', 'USABILITY', true, $logMsg);
        $this->testLogger->debug(ESAPILogger::USABILITY, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testDebugUsabilityFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Debug level test message. {$r}";
        $expected = $this->getExpected('DEBUG', 'USABILITY', false, $logMsg);
        $this->testLogger->debug(ESAPILogger::USABILITY, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testTraceUsabilitySuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Trace level test message. {$r}";
        $expected = $this->getExpected('TRACE', 'USABILITY', true, $logMsg);
        $this->testLogger->trace(ESAPILogger::USABILITY, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testTraceUsabilityFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Trace level test message. {$r}";
        $expected = $this->getExpected('TRACE', 'USABILITY', false, $logMsg);
        $this->testLogger->trace(ESAPILogger::USABILITY, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testFatalPerformanceSuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Fatal level test message. {$r}";
        $expected = $this->getExpected('FATAL', 'PERFORMANCE', true, $logMsg);
        $this->testLogger->fatal(ESAPILogger::PERFORMANCE, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testFatalPerformanceFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Fatal level test message. {$r}";
        $expected = $this->getExpected('FATAL', 'PERFORMANCE', false, $logMsg);
        $this->testLogger->fatal(ESAPILogger::PERFORMANCE, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testWarningPerformanceSuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Warning level test message. {$r}";
        $expected = $this->getExpected('WARNING', 'PERFORMANCE', true, $logMsg);
        $this->testLogger->warning(ESAPILogger::PERFORMANCE, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testWarningPerformanceFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Warning level test message. {$r}";
        $expected = $this->getExpected('WARNING', 'PERFORMANCE', false, $logMsg);
        $this->testLogger->warning(ESAPILogger::PERFORMANCE, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testErrorPerformanceSuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Error level test message. {$r}";
        $expected = $this->getExpected('ERROR', 'PERFORMANCE', true, $logMsg);
        $this->testLogger->error(ESAPILogger::PERFORMANCE, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testErrorPerformanceFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Error level test message. {$r}";
        $expected = $this->getExpected('ERROR', 'PERFORMANCE', false, $logMsg);
        $this->testLogger->error(ESAPILogger::PERFORMANCE, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testInfoPerformanceSuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Info level test message. {$r}";
        $expected = $this->getExpected('INFO', 'PERFORMANCE', true, $logMsg);
        $this->testLogger->info(ESAPILogger::PERFORMANCE, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testInfoPerformanceFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Info level test message. {$r}";
        $expected = $this->getExpected('INFO', 'PERFORMANCE', false, $logMsg);
        $this->testLogger->info(ESAPILogger::PERFORMANCE, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testDebugPerformanceSuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Debug level test message. {$r}";
        $expected = $this->getExpected('DEBUG', 'PERFORMANCE', true, $logMsg);
        $this->testLogger->debug(ESAPILogger::PERFORMANCE, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testDebugPerformanceFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Debug level test message. {$r}";
        $expected = $this->getExpected('DEBUG', 'PERFORMANCE', false, $logMsg);
        $this->testLogger->debug(ESAPILogger::PERFORMANCE, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testTracePerformanceSuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Trace level test message. {$r}";
        $expected = $this->getExpected('TRACE', 'PERFORMANCE', true, $logMsg);
        $this->testLogger->trace(ESAPILogger::PERFORMANCE, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testTracePerformanceFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Trace level test message. {$r}";
        $expected = $this->getExpected('TRACE', 'PERFORMANCE', false, $logMsg);
        $this->testLogger->trace(ESAPILogger::PERFORMANCE, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testFatalFunctionalitySuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Fatal level test message. {$r}";
        $expected = $this->getExpected('FATAL', 'FUNCTIONALITY', true, $logMsg);
        $this->testLogger->fatal(ESAPILogger::FUNCTIONALITY, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testFatalFunctionalityFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Fatal level test message. {$r}";
        $expected = $this->getExpected('FATAL', 'FUNCTIONALITY', false, $logMsg);
        $this->testLogger->fatal(ESAPILogger::FUNCTIONALITY, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testWarningFunctionalitySuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Warning level test message. {$r}";
        $expected = $this->getExpected('WARNING', 'FUNCTIONALITY', true, $logMsg);
        $this->testLogger->warning(ESAPILogger::FUNCTIONALITY, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testWarningFunctionalityFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Warning level test message. {$r}";
        $expected = $this->getExpected('WARNING', 'FUNCTIONALITY', false, $logMsg);
        $this->testLogger->warning(ESAPILogger::FUNCTIONALITY, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testErrorFunctionalitySuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Error level test message. {$r}";
        $expected = $this->getExpected('ERROR', 'FUNCTIONALITY', true, $logMsg);
        $this->testLogger->error(ESAPILogger::FUNCTIONALITY, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testErrorFunctionalityFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Error level test message. {$r}";
        $expected = $this->getExpected('ERROR', 'FUNCTIONALITY', false, $logMsg);
        $this->testLogger->error(ESAPILogger::FUNCTIONALITY, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testInfoFunctionalitySuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Info level test message. {$r}";
        $expected = $this->getExpected('INFO', 'FUNCTIONALITY', true, $logMsg);
        $this->testLogger->info(ESAPILogger::FUNCTIONALITY, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testInfoFunctionalityFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Info level test message. {$r}";
        $expected = $this->getExpected('INFO', 'FUNCTIONALITY', false, $logMsg);
        $this->testLogger->info(ESAPILogger::FUNCTIONALITY, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testDebugFunctionalitySuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Debug level test message. {$r}";
        $expected = $this->getExpected('DEBUG', 'FUNCTIONALITY', true, $logMsg);
        $this->testLogger->debug(ESAPILogger::FUNCTIONALITY, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testDebugFunctionalityFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Debug level test message. {$r}";
        $expected = $this->getExpected('DEBUG', 'FUNCTIONALITY', false, $logMsg);
        $this->testLogger->debug(ESAPILogger::FUNCTIONALITY, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testTraceFunctionalitySuccess() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Trace level test message. {$r}";
        $expected = $this->getExpected('TRACE', 'FUNCTIONALITY', true, $logMsg);
        $this->testLogger->trace(ESAPILogger::FUNCTIONALITY, true, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }

    function testTraceFunctionalityFailure() {
        $testMsg = null;
        $r = getRandomAlphaNumString(32);
        $logMsg = "Trace level test message. {$r}";
        $expected = $this->getExpected('TRACE', 'FUNCTIONALITY', false, $logMsg);
        $this->testLogger->trace(ESAPILogger::FUNCTIONALITY, false, $logMsg);
        $this->assertTrue($this->verifyLogEntry($expected, $testMsg), $testMsg);
    }


    function testCRLFRemoval() {
        $failMessage = null;
        if ($this->logfileIsReadable === false) {
             $failMessage = 'CRLF encoding could not be tested because we' .
                 ' could not read the logfile.';
        } else {
            $failMessage = 'CRLF Encoding FAILED!';
        }
        $testMsg = null;
        $r = getRandomAlphaNumString(16);
        $expected = "{$r}_{$r}";
        $this->testLogger->trace(ESAPILogger::SECURITY, true, "{$r}\n{$r}");
        $result = $this->verifyLogEntry($expected, $testMsg);
        if ($result === true) {
            $this->pass('CRLF Encoding is working!');
        } else {
            $this->fail($failMessage);
        }
    }


    function testHTMLEncoding() {
        $failMessage = null;
        if (ESAPI::getSecurityConfiguration()->getLogEncodingRequired() ===
            false
        ) {
            $failMessage =
                'HTML encoding cannot be tested until the LogEncodingRequired' .
                ' property is set to true. This test has not actually failed.';
        } else if ($this->logfileIsReadable === false) {
             $failMessage = 'HTML encoding could not be tested because we' .
                 ' could not read the logfile.';
        } else {
            $failMessage = 'HTML Encoding FAILED!';
        }
        $testMsg = null;
        $r = getRandomAlphaNumString(16);
        $expected = "{$r}&amp;{$r}";
        $this->testLogger->trace(ESAPILogger::SECURITY, true, "{$r}&{$r}");
        $result = $this->verifyLogEntry($expected, $testMsg);
        if ($result === true) {
            $this->pass('HTML Encoding is working!');
        } else {
            $this->fail($failMessage);
        }
    }


    /**
     * Helper function to read the logfile and match the supplied pattern.
     * It is expected that the supplied pattern contains a unique string to
     * avoid false positives.
     * Sets $msg with a descriptive message.
     *
     * @param  $expected the string pattern for a preg_match().
     * @param  &$msg reference to a string message which will be set here.
     *
     * @return boolean true if the pattern is matched in the logfile, otherwise
     *         false.
     */
    private function verifyLogEntry($expected, &$msg) {

        if ($this->logFileLoc === false) {
            $msg = 'Cannot find the logfile!';
            return false; // another fail because we couldn't find the logfile.
        }

        // read the logfile
        $result = fileContainsExpected($this->logFileLoc, $expected);

        if ($result === null) {
            $this->logFileLoc = false;
            $msg = "Failed to read the log file from {$this->logFileLoc}. All" .
                ' further LoggerTest tests will fail!';
            return false;
        } else if ($result === true) {
            $msg = 'Log file contains the expected entry. Logging to file' .
                    ' with the supplied parameters is verified.';
            return true;
        } else {
            $msg = 'Log file does not contain the expected entry. Cannot verify' .
                ' that logging to file is working for the supplied parameters.';
            return false;
        }
    }

    /**
     * Helper method uses the supplied parameters to construct a pattern for
     * preg_match and which attempts to model log entries.  It is important to
     * note that if changes are made to the format of log entries {@see
     * DefaultLogger::log()} then this method will need to be modified
     * accordingly.
     *
     * @param  $level string uppercase log level.
     * @param  $type string uppercase log entry type.
     * @param  $success boolean true for a success log event, false otherwise.
     * @param  $msg string log message as passed to the DefaultLogger method.
     * @param  $exceptionClassName string optional class name of an exception
     *         passed to DefaultLogger methods.
     *
     * @return string pattern (incl. terminators) for preg_match().
     */
    private function getExpected($level, $type, $success, $msg, $exceptionClassName = null) {
        $date = '[0-9-]{10,10} [0-9:]{8,8} [+-][0-9:]{5,5}';
        $success = $success ? '-SUCCESS' : '-FAILURE';
        $num = $this->testCount -1;
        $name = "LoggerTest #{$num}";
        $localSocket = '[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}:[0-9]{1,5}';
        $username = '[^@]+@';
        $remoteAddr = '[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}';
        $sessionID = '([0-9]{1,7}|SessionUnknown)';
        if ($exceptionClassName !== null) {
            $msg .= " exception '{$exceptionClassName}'";
        }
        return "{$date} {$level} {$name} {$type}{$success} {$localSocket} {$username}{$remoteAddr}\[ID:{$sessionID}\] {$msg}";
    }
}
