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
 * PHP version 5.2
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    Johannes B. Ullrich <jullrich@sans.edu>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   SVN: $Id$
 * @link      http://www.owasp.org/index.php/ESAPI
 */

/**
 * Implementations require ValidationException and IntrusionException.
 */
require_once dirname(__FILE__) . '/errors/IntrusionException.php';
require_once dirname(__FILE__) . '/errors/ValidationException.php';

/**
 * ValidationRule Interface.
 *
 * PHP version 5.2
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    Johannes B. Ullrich <jullrich@sans.edu>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   Release: @package_version@
 * @link      http://www.owasp.org/index.php/ESAPI
 */
interface ValidationRule
{

    /**
     * Sets the boolean allowNull property which, if set true, will allow empty
     * inputs to validate as true.
     *
     * @param  $flag boolean true if empty inputs should validate as true.
     */
    public function setAllowNull($flag);


    /**
     * Sets a descriptive name for the validator e.g. CreditCardNumber.
     *
     * @param  $typeName string name describing the validator.
     */
    public function setTypeName($typeName);


    /**
     * Gets the descriptive name for the validator.
     *
     * @return string name describing the validator.
     */
    public function getTypeName();


    /**
     * Sets an instance of an encoder class which should provide a
     * canonicalize method.
     *
     * @param  $encoder object which provides a canonicalize method.
     */
    public function setEncoder($encoder);


    /**
     * Asserts that the supplied $input is valid after canonicalization. Invalid
     * Inputs will cause a descriptive ValidationException to be thrown. Inputs
     * that are obviously an attack will cause an IntrusionException.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g., LoginPage_UsernameField). This value is used by
     *         any logging or error handling that is done with respect to the
     *         value passed in.
     * @param  $input The actual user input data to validate.
     */
    public function assertValid($context, $input);


    /**
     * Returns the canonicalized, valid input.
     * Throws ValidationException if the input is not valid or
     * IntrusionException if the input is an obvious attack.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g., LoginPage_UsernameField). This value is used by
     *         any logging or error handling that is done with respect to the
     *         value passed in.
     * @param  $input The actual string user input data to validate.
     *
     * @return string canonicalized, valid input.
     */
    public function getValid($context, $input);


    /**
     * Attempts to return valid canonicalized input.  If a ValidationException
     * is thrown, this method will return sanitized input which may or may not
     * have any similarity to the original input.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g., LoginPage_UsernameField). This value is used by
     *         any logging or error handling that is done with respect to the
     *         value passed in.
     * @param  $input The actual user input data to validate.
     *
     * @return valid, canonicalized input or sanitized input or a default value.
     *
     * @throws IntrusionException.
     */
    public function getSafe($context, $input);


    /**
     * Returns boolean true if the input is valid, false otherwise.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g., LoginPage_UsernameField). This value is used by
     *         any logging or error handling that is done with respect to the
     *         value passed in.
     * @param  $input The actual user input data to validate.
     *
     * @return boolean true if the input is valid, false otherwise.
     */
    public function isValid($context, $input);


    /**
     * The method is similar to getSafe except that it returns a harmless value
     * that may or may not have any similarity to the original input (in some
     * cases you may not care). In most cases this should be the same as the
     * getSafe method only instead of throwing an exception, return some default
     * value.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g., LoginPage_UsernameField). This value is used by
     *         any logging or error handling that is done with respect to the
     *         value passed in.
     * @param  $input The actual user input data to validate.
     *
     * @return a parsed version of the input or a default value.
     */
    public function sanitize($context, $input);


    /**
     * Returns the supplied input string after removing any characters not
     * present in the supplied whitelist.
     *
     * @param  $input string input to be filtered.
     * @param  $whitelist array or string of whitelist characters.
     *
     * @return string of characters from $input that are present in $whitelist.
     */
    public function whitelist($input, $list);


}
