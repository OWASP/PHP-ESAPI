<?php
/**
 * Some helper functions for use in tests.
 * 
 */


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
function fileContainsExpected($filename, $expected)
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
 * Helper returns the ESAPILogger log file path.
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
 * @param  $len integer length of the required string.
 *
 * @return string of $len alphanumeric characters.
 */
function getRandomAlphaNumString($len)
{
    if (empty($len)) {
        return null;
    }
    return ESAPI::getRandomizer()->getRandomString($len, Encoder::CHAR_ALPHANUMERICS);
}
