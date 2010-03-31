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
 * The Sanitizer interface defines a set of methods for sanitizing untrusted
 * input to remove all but a whitelist of characters. Implementors should feel
 * free to extend this interface to accommodate their own data formats.
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
interface Sanitizer
{    
    /**
     * Returns valid, "safe" HTML.
     * 
     * This implementation uses HTMLPurifier {@link http://htmlpurifier.org}. 
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. ProfilePage_Sig). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $input The actual user input data to validate.
     *
     * @return valid, "safe" HTML.
     */
    function getSanitizedHTML($context, $input);
    
    /**
     * Returns valid, "safe" email address.
     * 
     * This implementation uses a PHP filter {@link http://php.net/manual/en/filter.filters.sanitize.php}. 
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. ProfilePage_Sig). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $input The actual user input data to validate.
     *
     * @return valid, "safe" email address.
     */
    function getSanitizedEmailAddress($context, $input);
    
    /**
     * Returns valid, "safe" URL.
     * 
     * This implementation uses a PHP filter {@link http://php.net/manual/en/filter.filters.sanitize.php}. 
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. ProfilePage_Sig). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $input The actual user input data to validate.
     *
     * @return valid, "safe" URL.
     */
    function getSanitizedURL($context, $input);
    
}
