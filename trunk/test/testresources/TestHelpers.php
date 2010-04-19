<?php
/**
 * This file contains helper functions for use in SimpleTest unit tests.
 *
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
 * @package   ESAPI_Tests
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   SVN: $Id$
 * @link      http://www.owasp.org/index.php/ESAPI
 */


/**
 * Helper method which opens a file handle to the supplied path, reads it
 * line-by-line and performs preg_match on the line with the supplied regex. You can
 * supply a DateTime object along with a positive number of seconds and a date
 * format string and this function will further test lines which match $expected by
 * comparing the start of the line with the formatted date. The return will be true
 * if the line matches $expected and starts with a date between $date and $date +
 * $period seconds.  This is useful when $expected cannot be as explicit as desired
 * as in the case of IntrusionDetectorTest tests.
 *
 * @param string   $filename path to file.
 * @param string   $expected regex to match in the file.
 * @param DateTime $date     Optional DateTime object.
 * @param int      $period   Positive number of seconds.
 * @param string   $format   Date format string {@see Date}.
 *
 * @return bool|null True if the a line contains the expected parameters.
 */
function fileContainsExpected(
    $filename, $expected, $date = null, $period = 0, $format = 'Y-m-d H:i:s P'
) {
    if (empty($filename) || ! is_string($filename)) {
        return null;
    }
    if ($period === null || gettype($period) != 'integer') {
        $period = 0;
    }
    $baseDate = null;
    if ($date !== null) {
        $baseDate = $date->format($format);
        if ($baseDate === false) {
            return null;
        }
    }
    $f = fopen($filename, 'r');
    if ($f === false) {
        return null;
    }
    while (! feof($f)) {
        $line = fgets($f, 2048);
        if (preg_match("/{$expected}/", $line)) {
            if ($date !== null) {
                $d = new DateTime($baseDate);
                $count = $period;
                do {
                    $dateString = $d->format($format);
                    if (strncmp($line, $dateString, strlen($dateString)) === 0) {
                        fclose($f);
                        return true;
                    }
                    $d->modify('+1 second');
                    $count--;
                } while ($count >= 0);
                $d = null;
            } else {
                fclose($f);
                return true;
            }
        }
    }
    fclose($f);
    return false;
}


/**
 * Helper returns the ESAPILogger log file absolute path.
 *
 * @return string|bool RealPath for the ESAPI Auditor log file or
 *                     false.
 */
function getLogFileLoc()
{
    $filename = ESAPI::getSecurityConfiguration()->getLogFileName();
    return realpath($filename);
}


/**
 * Helper method returns a random string of alphanumeric characters of the
 * supplied length.
 *
 * @param int $len Length of the required string.
 *
 * @return string A string of $len alphanumeric characters.
 */
function getRandomAlphaNumString($len)
{
    if (empty($len)) {
        return null;
    }
    ESAPI::getEncoder();
    return ESAPI::getRandomizer()->getRandomString(
        $len,
        Encoder::CHAR_ALPHANUMERICS
    );
}
