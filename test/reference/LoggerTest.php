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
 * @author Andrew van der Stock (vanderaj @ owasp.org)
 * @created 2009
 */


require_once dirname(__FILE__).'/../../src/ESAPI.php';


class LoggerTest extends UnitTestCase {

    var $testCount = 0;
    var $testLogger= null;

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

    function testFatal() {
        $this->testLogger->fatal(
            ESAPILogger::SECURITY, true, 'Fatal test message.'
        );
        $this->testLogger->fatal(
            ESAPILogger::SECURITY, true, 'Fatal test message.', null
        );
    }

    function testWarning() {
        $this->testLogger->warning(
            ESAPILogger::SECURITY, true, 'Warn test message.'
        );
        $this->testLogger->warning(
            ESAPILogger::SECURITY, true, 'Warn test message.', null
        );
    }

    function testError() {
        $this->testLogger->error(
            ESAPILogger::SECURITY, true, 'Error test message.'
        );
        $this->testLogger->error(
            ESAPILogger::SECURITY, true, 'Error test message.', null
        );
    }

    function testInfo() {
        $this->testLogger->info(
            ESAPILogger::SECURITY, true, 'Info test message.'
        );
        $this->testLogger->info(
            ESAPILogger::SECURITY, true, 'Info test message.', null
        );
    }

    function testDebug() {
        $this->testLogger->debug(
            ESAPILogger::SECURITY, true, 'Debug test message.'
        );
        $this->testLogger->debug(
            ESAPILogger::SECURITY, true, 'Debug test message.', null
        );
    }

    function testTrace() {
        $this->testLogger->trace(
            ESAPILogger::SECURITY, true, 'Trace test message.'
        );
        $this->testLogger->trace(
            ESAPILogger::SECURITY, true, 'Trace test message.', null
        );
    }

}
