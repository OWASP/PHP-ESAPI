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
 * @author    Jeff Williams <jeff.williams@aspectsecurity.com>
 * @author    Andrew van der Stock <vanderaj@owasp.org>
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
require_once dirname(__FILE__).'/errors/IntrusionException.php';
require_once dirname(__FILE__).'/errors/ValidationException.php';

/**
 * The Validator interface defines a set of methods for canonicalizing and
 * validating untrusted input. Implementors should feel free to extend this
 * interface to accommodate their own data formats. Rather than throw
 * exceptions, this interface returns boolean results because not all
 * validation problems are security issues. Boolean returns allow developers to
 * handle both valid and invalid results more cleanly than exceptions.
 *
 * <img src="doc-files/Validator.jpg">
 *
 * Implementations must adopt a "whitelist" approach to validation where a
 * specific pattern or character set is matched. "Blacklist" approaches that
 * attempt to identify the invalid or disallowed characters are much more likely
 * to allow a bypass with encoding or other tricks.
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    Jeff Williams <jeff.williams@aspectsecurity.com>
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @author    Johannes B. Ullrich <jullrich@sans.edu>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   Release: @package_version@
 * @link      http://www.owasp.org/index.php/ESAPI
 */
interface Validator
{
    /**
     * Returns true if input is valid according to the specified type after
     * canonicalization. The type parameter must be the name of a defined type
     * in the ESAPI configuration or a valid regular expression pattern.
     *
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. LoginPage_UsernameField). This 
     *                          value is used by any logging or error handling 
     *                          that is done with respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param string $type      The regular expression name that maps to the actual 
     *                          regular expression from "ESAPI.xml" or an actual 
     *                          regular expression.
     * @param int    $maxLength The maximum post-canonicalized String length allowed.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or an
     *                          empty string will be legal. If allowNull is false 
     *                          then NULL or an empty String will throw a 
     *                          ValidationException.
     *
     * @return bool TRUE if the input is valid, FALSE otherwise.
     */
    public function isValidInput($context, $input, $type, $maxLength, $allowNull);

    /**
     * Asserts that the input is valid according to the supplied type after
     * canonicalization. The type parameter must be the name of a defined type
     * in the ESAPI configuration or a valid regular expression pattern.
     * Invalid input will generate a descriptive ValidationException and input
     * that is clearly an attack will generate a descriptive IntrusionException.
     *
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. LoginPage_UsernameField). This 
     *                          value is used by any logging or error handling 
     *                          that is done with respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param string $type      The regular expression name that maps to the actual
     *                          regular expression from "ESAPI.xml" or an actual 
     *                          regular expression.
     * @param int    $maxLength The maximum post-canonicalized String length allowed.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or an
     *                          empty string will be legal. If allowNull is false 
     *                          then NULL or an empty String will throw a 
     *                          ValidationException.
     *
     * @return Does not return a value.
     * @throws ValidationException Thrown if the input is invalid.
     * @throws IntrusionException  Thrown if an intrusion is detected.
     */
    public function assertValidInput($context, $input, $type, 
        $maxLength, $allowNull
    );

    /**
     * Returns true if the canonicalized input is a valid date according to the
     * specified date format string, or false otherwise.
     *
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. ProfilePage_DoB). This value is used
     *                          by any logging or error handling that is done with 
     *                          respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param string $format    Required formatting of date inputted {@see date}.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is 
     *                          false then NULL or an empty String will throw a 
     *                          ValidationException.
     *
     * @return bool TRUE if the input is valid, FALSE otherwise.
     */
    public function isValidDate($context, $input, $format, $allowNull);

    /**
     * Asserts that the canonicalized input is a valid date according to the
     * specified date format string. Invalid input will generate a descriptive
     * ValidationException and input that is clearly an attack will generate a
     * descriptive IntrusionException.
     *
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. ProfilePage_DoB). This value is u
     *                          sed by any logging or error handling that is done 
     *                          with respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param string $format    Required formatting of date inputted {@see strftime}.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is 
     *                          false then NULL or an empty String will throw a 
     *                          ValidationException.
     *
     * @return Does not return a value.
     * @throws ValidationException Thrown if the input is invalid.
     * @throws IntrusionException  Thrown if an intrusion is detected.
     */
    public function assertValidDate($context, $input, $format, $allowNull);

    /**
     * Returns true if the canonicalized input is valid, "safe" HTML.
     * 
     * Implementors should reference the OWASP AntiSamy project for ideas on how
     * to do HTML validation in a whitelist way, as this is an extremely
     * difficult problem. It is recommended that PHP implementations make use of
     * HTMLPurifier {@link http://htmlpurifier.org}.
     * 
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. ProfilePage_Sig). This value is 
     *                          used by any logging or error handling that is done 
     *                          with respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param int    $maxLength The maximum post-canonicalized String length 
     *                          allowed.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is false
     *                          then NULL or an empty String will throw a 
     *                          ValidationException.
     * 
     * @return bool TRUE if the input is valid, FALSE otherwise.
     */
    public function isValidHTML($context, $input, $maxLength, $allowNull);
    
    /**
     * Asserts that the canonicalized input is valid, "safe" HTML.
     * Invalid input will generate a descriptive ValidationException and input
     * that is clearly an attack will generate a descriptive IntrusionException.
     * 
     * Implementors should reference the OWASP AntiSamy project for ideas on how
     * to do HTML validation in a whitelist way, as this is an extremely
     * difficult problem. It is recommended that PHP implementations make use of
     * HTMLPurifier {@link http://htmlpurifier.org}. 
     * 
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. ProfilePage_Sig). This value is 
     *                          used by any logging or error handling that is done 
     *                          with respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param int    $maxLength The maximum post-canonicalized String length allowed.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is 
     *                          false then NULL or an empty String will throw a 
     *                          ValidationException.
     *
     * @return Does not return a value.
     * @throws ValidationException Thrown if the input is invalid.
     * @throws IntrusionException  Thrown if an intrusion is detected.
     */
    public function assertValidHTML($context, $input, $maxLength, $allowNull);
    
    /**
     * Returns true if the canonicalized input is a valid Credit Card Number.
     * 
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. PurchasePage_CCNum). This value 
     *                          is used by any logging or error handling that is 
     *                          done with respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is 
     *                          false then NULL or an empty String will throw a 
     *                          ValidationException.
     *
     * @return bool TRUE if the input is valid, FALSE otherwise.
     */
    public function isValidCreditCard($context, $input, $allowNull);
    
    /**
     * Asserts that the canonicalized input is a valid Credit Card Number.
     * Invalid input will generate a descriptive ValidationException and input
     * that is clearly an attack will generate a descriptive IntrusionException.
     * 
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. PurchasePage_CCNum). This value 
     *                          is used by any logging or error handling that is 
     *                          done with respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is 
     *                          false then NULL or an empty String will throw a 
     *                          ValidationException.
     *
     * @return Does not return a value.
     * @throws ValidationException Thrown if the input is invalid.
     * @throws IntrusionException  Thrown if an intrusion is detected.
     */
    public function assertValidCreditCard($context, $input, $allowNull);
    
    /**
     * Returns true if the canonicalized input is a valid directory path.
     * 
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. IncludeFile). This value is used 
     *                          by any logging or error handling that is done with 
     *                          respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is 
     *                          false then NULL or an empty String will throw a 
     *                          ValidationException.
     *
     * @return bool TRUE if the input is valid, FALSE otherwise.
     */
    public function isValidDirectoryPath($context, $input, $allowNull);
    
    /**
     * Asserts that the canonicalized input is a valid directory path.
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException.
     *  
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. IncludeFile). This value is used 
     *                          by any logging or error handling that is done with 
     *                          respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is 
     *                          false then NULL or an empty String will throw a 
     *                          ValidationException.
     * 
     * @return Does not return a value.
     * @throws ValidationException Thrown if the input is invalid.
     * @throws IntrusionException  Thrown if an intrusion is detected.
     */
    public function assertValidDirectoryPath($context, $input, $allowNull);
    
    /**
     * Returns true if the canonicalized input is a valid file name.
     * 
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. IncludeFile). This value is used 
     *                          by any logging or error handling that is done with 
     *                          respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is 
     *                          false then NULL or an empty String will throw a 
     *                          ValidationException.
     * 
     * @return bool TRUE if the input is valid, FALSE otherwise.
     */
    public function isValidFileName($context, $input, $allowNull);
     
    /**
     * Asserts that the canonicalized input is a valid file name.
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException.
     *
     * Implementors should check for allowed file extensions here, as well as
     * allowed file name characters, as declared in "ESAPI.xml".
     * 
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. IncludeFile). This value is used 
     *                          by any logging or error handling that is done with 
     *                          respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is 
     *                          false then NULL or an empty String will throw a 
     *                          ValidationException.
     * 
     * @return Does not return a value.
     * @throws ValidationException Thrown if the input is invalid.
     * @throws IntrusionException  Thrown if an intrusion is detected.
     */
    public function assertValidFileName($context, $input, $allowNull);
    
    /**
     * Returns true if the canonicalized input is a valid, real number within
     * the specified range minValue to maxValue.
     * 
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. PurchasePage_Quantity). This value 
     *                          is used by any logging or error handling that is done
     *                          with respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param int    $minValue  The numeric lowest legal value for input.
     * @param int    $maxValue  The numeric highest legal value for input.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is 
     *                          false then NULL or an empty String will throw a 
     *                          ValidationException.
     * 
     * @return bool TRUE if the input is valid, FALSE otherwise.
     */
    public function isValidNumber($context, $input, $minValue, $maxValue, 
        $allowNull
    );
   
    /**
     * Asserts that the canonicalized input is a valid, real number within
     * the specified range minValue to maxValue.
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException.
     *
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. PurchasePage_Quantity). This value 
     *                          is used by any logging or error handling that is 
     *                          done with respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param int    $minValue  The numeric lowest legal value for input.
     * @param int    $maxValue  The numeric highest legal value for input.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is 
     *                          false then NULL or an empty String will throw a 
     *                          ValidationException.
     * 
     * @return Does not return a value.
     * @throws ValidationException Thrown if the input is invalid.
     * @throws IntrusionException  Thrown if an intrusion is detected.
     */
    public function assertValidNumber($context, $input, $minValue, $maxValue,
        $allowNull
    );
   
    /**
     * Returns true if the canonicalized input is a valid integer within the
     * specified range minValue to maxValue.
     * 
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. PurchasePage_Quantity). This value 
     *                          is used by any logging or error handling that is 
     *                          done with respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param int    $minValue  The numeric lowest legal value for input.
     * @param int    $maxValue  The numeric highest legal value for input.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is 
     *                          false then NULL or an empty String will throw a 
     *                          ValidationException.
     * 
     * @return bool TRUE if the input is valid, FALSE otherwise.
     */
    public function isValidInteger($context, $input, $minValue, $maxValue, 
        $allowNull
    );
    
    /**
     * Asserts that the canonicalized input is a valid integer within the
     * specified range minValue to maxValue.
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException.
     * 
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. PurchasePage_Quantity). This value
     *                          is used by any logging or error handling that is 
     *                          done with respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param int    $minValue  The numeric lowest legal value for input.
     * @param int    $maxValue  The numeric highest legal value for input.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is 
     *                          false then NULL or an empty String will throw a 
     *                          ValidationException.
     * 
     * @return Does not return a value.
     * @throws ValidationException Thrown if the input is invalid.
     * @throws IntrusionException  Thrown if an intrusion is detected.
     */
    public function assertValidInteger($context, $input, $minValue, $maxValue, 
        $allowNull
    );
   
    /**
     * Returns true if the canonicalized input is a valid double within the
     * specified range minValue to maxValue.
     * 
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. PurchasePage_Quantity). This value 
     *                          is used by any logging or error handling that is 
     *                          done with respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param int    $minValue  The numeric lowest legal value for input.
     * @param int    $maxValue  The numeric highest legal value for input.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is false 
     *                          then NULL or an empty String will throw a 
     *                          ValidationException.
     * 
     * @return bool TRUE if the input is valid, FALSE otherwise.
     */
    public function isValidDouble($context, $input, $minValue, $maxValue, 
        $allowNull
    );
    
    /**
     * Asserts that the canonicalized input is a valid double within the
     * specified range minValue to maxValue.
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException.
     * 
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. PurchasePage_Quantity). This value 
     *                          is used by any logging or error handling that is 
     *                          done with respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param int    $minValue  The numeric lowest legal value for input.
     * @param int    $maxValue  The numeric highest legal value for input.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is 
     *                          false then NULL or an empty String will throw a 
     *                          ValidationException.
     * 
     * @return Does not return a value.
     * @throws ValidationException Thrown if the input is invalid.
     * @throws IntrusionException  Thrown if an intrusion is detected.
     */
    public function assertValidDouble($context, $input, $minValue, $maxValue, 
        $allowNull
    );
   
    /**
     * Returns true if input is valid file content.
     * This is a good place to check for max file size, allowed character sets
     * and do virus scans.
     * 
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. UploadPage_Avatar). This value 
     *                          is used by any logging or error handling that is 
     *                          done with respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param int    $maxBytes  The maximum number of bytes allowed in a legal file.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is 
     *                          false then NULL or an empty String will throw a 
     *                          ValidationException.
     * 
     * @return bool TRUE if the input is valid, FALSE otherwise.
     */
    public function isValidFileContent($context, $input, $maxBytes, $allowNull);
   
    /**
     * Asserts that the input is valid file content.
     * This is a good place to check for max file size, allowed character sets
     * and do virus scans.
     * Invalid input will generate a descriptive ValidationException.
     * 
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. UploadPage_Avatar). This value 
     *                          is used by any logging or error handling that is 
     *                          done with respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param int    $maxBytes  The maximum number of bytes allowed in a legal file.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is 
     *                          false then NULL or an empty String will throw a 
     *                          ValidationException.
     * 
     * @return Does not return a value.
     * @throws ValidationException Thrown if the input is invalid.
     * @throws IntrusionException  Thrown if an intrusion is detected.
     */
    public function assertValidFileContent($context, $input, $maxBytes, 
        $allowNull
    );
    
    /**
     * Returns true if a file upload has a valid name, path, and content.
     * 
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. UploadPage_Avatar). This value is 
     *                          used by any logging or error handling that is done 
     *                          with respect to the value passed in.
     * @param string $filepath  The file path of the uploaded file.
     * @param string $filename  The filename of the uploaded file
     * @param string $content   A string containing the content of the uploaded file.
     * @param int    $maxBytes  The max number of bytes allowed for a legal file 
     *                          upload.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or an
     *                          empty string will be legal. If allowNull is false 
     *                          then NULL or an empty String will throw a 
     *                          ValidationException.
     * 
     * @return bool TRUE if the input is valid, FALSE otherwise.
     */
    public function isValidFileUpload($context, $filepath, $filename, $content, 
        $maxBytes, $allowNull
    );
   
    /**
     * Asserts that a file upload has a valid name, path, and content.
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException.
     * 
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. UploadPage_Avatar). This value 
     *                          is used by any logging or error handling that is 
     *                          done with respect to the value passed in.
     * @param string $filepath  The file path of the uploaded file.
     * @param string $filename  The filename of the uploaded file
     * @param string $content   A string containing the content of the uploaded file.
     * @param int    $maxBytes  The max number of bytes allowed for a legal file 
     *                          upload.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is 
     *                          false then NULL or an empty String will throw a 
     *                          ValidationException.
     * 
     * @return Does not return a value.
     * @throws ValidationException Thrown if the input is invalid.
     * @throws IntrusionException  Thrown if an intrusion is detected.
     */
    public function assertValidFileUpload($context, $filepath, $filename, $content, 
        $maxBytes, $allowNull
    );
    
    /**
     * Validate the current HTTP request by comparing parameters, headers and
     * cookies to a predefined whitelist of allowed characters.
     * For the methods to retrieve the whitelists {@see SecurityConfiguration}.
     * 
     * @return bool TRUE if the input is valid, FALSE otherwise.
     */
    public function isValidHTTPRequest();
    
    /**
     * Asserts that the current HTTP request is valid by comparing parameters,
     * headers and cookies to a predefined whitelist of allowed characters.
     * For the methods to retrieve the whitelists {@see SecurityConfiguration}.
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException.
     * 
     * @return Does not return a value.
     * @throws ValidationException Thrown if the input is invalid.
     * @throws IntrusionException  Thrown if an intrusion is detected.
     */
    public function assertValidHTTPRequest();
   
    /**
     * Returns true if the canonicalized input exactly matches a list item.
     * 
     * @param string $context A descriptive name of the parameter that you are
     *                        validating (e.g. Contact_Recipient). This value 
     *                        is used by any logging or error handling that 
     *                        is done with respect to the value passed in.
     * @param string $input   The value to search for in the supplied list.
     * @param array  $list    The list to search for the supplied input.
     * 
     * @return bool TRUE if the input is valid, FALSE otherwise.
     */
    public function isValidListItem($context, $input, $list);
    
    /**
     * Asserts that the canonicalized input exactly matches a list item.
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException. 
     * 
     * @param string $context A descriptive name of the parameter that you are
     *                        validating (e.g. Contact_Recipient). This value 
     *                        is used by any logging or error handling that is 
     *                        done with respect to the value passed in.
     * @param string $input   The value to search for in the supplied list.
     * @param array  $list    The list to search for the supplied input.
     * 
     * @return Does not return a value.
     * @throws ValidationException Thrown if the input is invalid.
     * @throws IntrusionException  Thrown if an intrusion is detected.
     */
    public function assertValidListItem($context, $input, $list);
    
    /**
     * Returns true if the parameters in the current request contain all
     * required parameters and only the optional parameters specified.
     * 
     * @param string $context  A descriptive name of the parameter that you are
     *                         validating (e.g. Request_Params). This value is 
     *                         used by any logging or error handling that is 
     *                         done with respect to the value passed in.
     * @param array  $required The parameters that are required to be in HTTP 
     *                         request.
     * @param array  $optional The additional parameters that may be in HTTP 
     *                         request.
     * 
     * @return bool TRUE if the input is valid, FALSE otherwise.
     */
    public function isValidHTTPRequestParameterSet($context, $required, $optional);
    
    /**
     * Asserts that the parameters in the current request contain all required
     * parameters and only the optional parameters specified.
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException. 
     * 
     * @param string $context  A descriptive name of the parameter that you are
     *                         validating (e.g. Request_Params). This value is 
     *                         used by any logging or error handling that is done 
     *                         with respect to the value passed in.
     * @param array  $required The parameters that are required to be in HTTP 
     *                         request.
     * @param array  $optional The additional parameters that may be in HTTP 
     *                         request.
     * 
     * @return Does not return a value.
     * @throws ValidationException Thrown if the input is invalid.
     * @throws IntrusionException  Thrown if an intrusion is detected.
     */
    public function assertValidHTTPRequestParameterSet($context, $required, 
        $optional
    );
    
    /**
     * Returns true if the canonicalized input contains no more than the number
     * of valid printable ASCII characters specified.
     * 
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. ASCIIArt_Submission). This value 
     *                          is used by any logging or error handling that is 
     *                          done with respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param int    $maxLength The maximum number of canonicalized ascii characters
     *                          allowed in a legal input.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or an
     *                          empty string will be legal. If allowNull is false 
     *                          then NULL or an empty String will throw a 
     *                          ValidationException.
     * 
     * @return bool TRUE if the input is valid, FALSE otherwise.
     */
    public function isValidPrintable($context, $input, $maxLength, $allowNull);
   
    /**
     * Asserts that the canonicalized input contains no more than the number of
     * valid printable ASCII characters specified.
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException. 
     * 
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. ASCIIArt_Submission). This value 
     *                          is used by any logging or error handling that is 
     *                          done with respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param int    $maxLength The maximum number of canonicalized ascii characters
     *                          allowed in a legal input.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is 
     *                          false then NULL or an empty String will throw a 
     *                          ValidationException.
     * 
     * @return Does not return a value.
     * @throws ValidationException Thrown if the input is invalid.
     * @throws IntrusionException  Thrown if an intrusion is detected.
     */
    public function assertValidPrintable($context, $input, $maxLength, $allowNull);
    
    /**
     * Returns true if the canonicalized input is a valid redirect location as
     * defined in "ESAPI.xml".
     * 
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. IncludeFile). This value is used 
     *                          by any logging or error handling that is done with 
     *                          respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is 
     *                          false then NULL or an empty String will throw a 
     *                          ValidationException.
     * 
     * @return bool TRUE if the input is valid, FALSE otherwise.
     */
    public function isValidRedirectLocation($context, $input, $allowNull);

    /**
     * Asserts that the canonicalized input is a valid redirect location as
     * defined in "ESAPI.xml".
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException.
     * 
     * @param string $context   A descriptive name of the parameter that you are
     *                          validating (e.g. IncludeFile). This value is used 
     *                          by any logging or error handling that is done with 
     *                          respect to the value passed in.
     * @param string $input     The actual user input data to validate.
     * @param bool   $allowNull If allowNull is true then an input that is NULL or 
     *                          an empty string will be legal. If allowNull is 
     *                          false then NULL or an empty String will throw a 
     *                          ValidationException.
     * 
     * @return Does not return a value.
     * @throws ValidationException Thrown if the input is invalid.
     * @throws IntrusionException  Thrown if an intrusion is detected.
     */
    public function assertValidRedirectLocation($context, $input, $allowNull);

    /**
     * Reads from an input stream until end-of-line or a maximum number of
     * characters. This method protects against the inherent denial of service
     * attack in reading until the end of a line. If an attacker doesn't ever
     * send a newline character, then a normal input stream reader will read
     * until all memory is exhausted and the platform throws an OutOfMemoryError
     * and probably terminates.
     * 
     * @param string $inputStream The InputStream from which to read data
     * @param int    $maxLength   Maximum number of characters allowed to be read 
     *                            in per line
     * 
     * @return Does not return a value.
     * @throws ValidationException Thrown if the input is invalid.
     * @throws IntrusionException  Thrown if an intrusion is detected.
     */
    function safeReadLine($inputStream, $maxLength);

}
