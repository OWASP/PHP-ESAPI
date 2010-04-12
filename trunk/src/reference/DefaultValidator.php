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
 * @author    Jeff Williams <jeff.williams@aspectsecurity.com>
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @author    Johannes B. Ullrich <jullrich@sans.edu>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */


/**
 * Require the Validator and the various ValidationRule implementations.
 */
require_once dirname ( __FILE__ ) . '/../Validator.php';
require_once dirname ( __FILE__ ) . '/validation/StringValidationRule.php';
require_once dirname ( __FILE__ ) . '/validation/CreditCardValidationRule.php';
require_once dirname ( __FILE__ ) . '/validation/HTMLValidationRule.php';
require_once dirname ( __FILE__ ) . '/validation/NumberValidationRule.php';
require_once dirname ( __FILE__ ) . '/validation/IntegerValidationRule.php';
require_once dirname ( __FILE__ ) . '/validation/DateValidationRule.php';


/**
 * Reference Implementation of the Validator interface.
 *
 * PHP version 5.2.9
 *
 * @category  OWASP
 * @package   ESAPI
 * @version   1.0
 * @author    Jeff Williams <jeff.williams@aspectsecurity.com>
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @author    Johannes B. Ullrich <jullrich@sans.edu>
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */
class DefaultValidator implements Validator
{

    private $rules = null;
    private $logger = null;
    private $encoder = null;
    private $fileValidator = null;
    
    const MAX_PARAMETER_NAME_LENGTH = 100;
    const MAX_PARAMETER_VALUE_LENGTH = 65535;
    
    public function __construct()
    {
        global $ESAPI;
        $this->logger = ESAPI::getLogger('DefaultValidator');
        $this->encoder = ESAPI::getEncoder();
    }


    /**
     * Returns true if input is valid according to the specified type after
     * canonicalization. The type parameter must be the name of a defined type
     * in the ESAPI configuration or a valid regular expression pattern.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. LoginPage_UsernameField). This value is used by
     *         any logging or error handling that is done with respect to the
     *         value passed in.
     * @param  $input The actual user input data to validate.
     * @param  $type The regular expression name that maps to the actual regular
     *         expression from "ESAPI.xml" or an actual regular expression.
     * @param  $maxLength The maximum post-canonicalized String length allowed.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     *
     * @return true, if the input is valid based on the rules set by 'type' or
     *         false otherwise.
     */
    public function isValidInput($context, $input, $type, $maxLength, $allowNull)
    {
        try
        {
            $this->_assertValidInput(
                $context, $input, $type, $maxLength, $allowNull
            );
        }
        catch ( Exception $e )
        {
            return false;
        }
        
        return true;
    }


    /**
     * Asserts that the input is valid according to the supplied type after
     * canonicalization. The type parameter must be the name of a defined type
     * in the ESAPI configuration or a valid regular expression pattern.
     * Invalid input will generate a descriptive ValidationException and input
     * that is clearly an attack will generate a descriptive IntrusionException.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. LoginPage_UsernameField). This value is used by
     *         any logging or error handling that is done with respect to the
     *         value passed in.
     * @param  $input The actual user input data to validate.
     * @param  $type The regular expression name that maps to the actual regular
     *         expression from "ESAPI.xml" or an actual regular expression.
     * @param  $maxLength The maximum post-canonicalized String length allowed.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     *
     * @return null.
     *
     * @throws ValidationException, IntrusionException.
     */
    private function _assertValidInput($context, $input, $type, $maxLength, $allowNull)
    {
        $validationRule = new StringValidationRule($type, $this->encoder);
        
        $config = ESAPI::getSecurityConfiguration();
        $pattern = $config->getValidationPattern($type);
        if ($pattern != null)
        {
            $validationRule->addWhitelistPattern($pattern);
        }
        else
        {
            $validationRule->addWhitelistPattern($type);
        }

        $validationRule->setMaximumLength($maxLength);
        $validationRule->setAllowNull($allowNull);

        $validationRule->assertValid($context, $input);
        
        return null; 
    }


    /**
     * Returns true if the canonicalized input is a valid date according to the
     * specified date format string, or false otherwise.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. ProfilePage_DoB). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $input The actual user input data to validate.
     * @param  $format Required formatting of date inputted {@see date}.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     *
     * @return true if input is a valid date according to the format specified
     *         by $format, or false otherwise.
     */
    public function isValidDate($context, $input, $format, $allowNull)
    {
        try
        {
            $this->_assertValidDate($context, $input, $format, $allowNull);
        }
        catch ( Exception $e )
        {
            return false;
        }
        
        return true;
    }


    /**
     * Asserts that the canonicalized input is a valid date according to the
     * specified date format string. Invalid input will generate a descriptive
     * ValidationException and input that is clearly an attack will generate a
     * descriptive IntrusionException.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. ProfilePage_DoB). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $input The actual user input data to validate.
     * @param  $format Required formatting of date inputted {@see strftime}.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     *
     * @return null.
     *
     * @throws ValidationException.
     * @throws IntrusionException.
     */
    private function _assertValidDate($context, $input, $format, $allowNull)
    {
        $dvr = new DateValidationRule('DateValidator', $this->encoder, $format);
        $dvr->setAllowNull($allowNull);
        
        $dvr->assertValid($context, $input);
        
        return null;
    }


    /**
     * Returns true if the canonicalized input is valid, "safe" HTML.
     * 
     * Implementors should reference the OWASP AntiSamy project for ideas on how
     * to do HTML validation in a whitelist way, as this is an extremely
     * difficult problem. It is recommended that PHP implementations make use of
     * HTMLPurifier {@link http://htmlpurifier.org}.
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. ProfilePage_Sig). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $input The actual user input data to validate.
     * @param  $maxLength The maximum post-canonicalized String length allowed.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     * 
     * @return true if input is a valid safe HTML, or false otherwise.
     */
    public function isValidHTML($context, $input, $maxLength, $allowNull)
    {
        try
        {
            $this->_assertValidHTML($context, $input, $maxLength, $allowNull);
        }
        catch (Exception $e)
        {
            return false;
        }
        
        return true;
    }
    
    
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
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. ProfilePage_Sig). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $input The actual user input data to validate.
     * @param  $maxLength The maximum post-canonicalized String length allowed.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     *
     * @return null.
     *
     * @throws ValidationException.
     * @throws IntrusionException.
     */
    private function _assertValidHTML($context, $input, $maxLength, $allowNull)
    {
        $hvr = new HTMLValidationRule('HTML_Validator', $this->encoder);
        $hvr->setMaximumLength($maxLength);
        $hvr->setAllowNull($allowNull);
        
        $hvr->assertValid($context, $input);
        
        return null;
    }
    
    
    /**
     * Returns true if the canonicalized input is a valid Credit Card Number.
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. PurchasePage_CCNum). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $input The actual user input data to validate.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     *
     * @return true if the input is a valid Credit Card number, otherwise false.
     */
    public function isValidCreditCard($context, $input, $allowNull)
    {
        try
        {
            $this->_assertValidCreditCard($context, $input, $allowNull);
        }
        catch (Exception $e)
        {
            return false;
        }
        
        return true;
    }
    
    
    /**
     * Asserts that the canonicalized input is a valid Credit Card Number.
     * Invalid input will generate a descriptive ValidationException and input
     * that is clearly an attack will generate a descriptive IntrusionException.
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. PurchasePage_CCNum). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $input The actual user input data to validate.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     *
     * @return null.
     *
     * @throws ValidationException.
     * @throws IntrusionException.
     */
    private function _assertValidCreditCard($context, $input, $allowNull)
    {
        $ccvr = new CreditCardValidationRule('CreditCard', $this->encoder);
        $ccvr->setAllowNull($allowNull);
        
        $ccvr->assertValid($context, $input);
         
        return null;
    }
    
    
    /**
     * Returns true if the canonicalized input is a valid directory path.
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. IncludeFile). This value is used by any logging
     *         or error handling that is done with respect to the value passed
     *         in.
     * @param  $input The actual user input data to validate.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     *
     * 
     * @return true if the canonicalized input is a valid directory path or
     *         false otherwise.
     */
    public function isValidDirectoryPath($context, $input, $allowNull)
    {
        try
        {
            $this->_assertValidDirectoryPath($context, $input, $allowNull);
        }
        catch (Exception $e)
        {
            return false;
        }
        
        return true;
    }
    
    
    /**
     * Asserts that the canonicalized input is a valid directory path.
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException.
     *  
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. IncludeFile). This value is used by any logging
     *         or error handling that is done with respect to the value passed
     *         in.
     * @param  $input The actual user input data to validate.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     * 
     * @return null.
     * 
     * @throws ValidationException.
     * @throws IntrusionException.
     */
    private function _assertValidDirectoryPath($context, $input, $allowNull)
    {
        throw new EnterpriseSecurityException(
            'Method Not implemented',
            'assertValidDirectoryPath method not implemented'
        );
    }
    
    
    /**
     * Returns true if the canonicalized input is a valid file name.
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. IncludeFile). This value is used by any logging
     *         or error handling that is done with respect to the value passed
     *         in.
     * @param  $input The actual user input data to validate.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     * 
     * @return true if the canonicalized input is a valid file name or false
     *         otherwise.
     */
    public function isValidFileName($context, $input, $allowNull)
    {
        try
        {
            $this->_assertValidFileName($context, $input, $allowNull);
        }
        catch (Exception $e)
        {
            return false;
        }
        
        return true;
    }
    

    /**
     * Asserts that the canonicalized input is a valid file name.
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException.
     *
     * Implementors should check for allowed file extensions here, as well as
     * allowed file name characters, as declared in "ESAPI.xml".
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. IncludeFile). This value is used by any logging
     *         or error handling that is done with respect to the value passed
     *         in.
     * @param  $input The actual user input data to validate.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     * 
     * @return null.
     * 
     * @throws ValidationException.
     * @throws IntrusionException.
     */
    private function _assertValidFileName($context, $input, $allowNull)
    {
        throw new EnterpriseSecurityException(
            'Method Not implemented',
            'assertValidFileName method not implemented'
        );
    }
    
    
    /**
     * Returns true if the canonicalized input is a valid, real number within
     * the specified range minValue to maxValue.
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. PurchasePage_Quantity). This value is used by
     *         any logging or error handling that is done with respect to the
     *         value passed in.
     * @param  $input The actual user input data to validate.
     * @param  $minValue numeric lowest legal value for input.
     * @param  $maxValue numeric highest legal value for input.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     * 
     * @return true if the canonicalized input is a valid number or false
     *         otherwise.
     */
    public function isValidNumber($context, $input, $minValue, $maxValue, $allowNull)
    {
        try
        {
            $this->_assertValidNumber($context, $input, $minValue, $maxValue, $allowNull);
        }
        catch (Exception $e)
        {
            return false;
        }
        
        return true;
    }
    

    /**
     * Asserts that the canonicalized input is a valid, real number within
     * the specified range minValue to maxValue.
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. PurchasePage_Quantity). This value is used by
     *         any logging or error handling that is done with respect to the
     *         value passed in.
     * @param  $input The actual user input data to validate.
     * @param  $minValue numeric lowest legal value for input.
     * @param  $maxValue numeric highest legal value for input.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     * 
     * @return null.
     * 
     * @throws ValidationException.
     * @throws IntrusionException.
     */
    private function _assertValidNumber($context, $input, $minValue, $maxValue, $allowNull)
    {
        $nvr = new NumberValidationRule(
            'NumberValidator', $this->encoder, $minValue, $maxValue
        );
        $nvr->setAllowNull($allowNull);
        
        $nvr->assertValid($context, $input);
        
        return null;
    }
    

    /**
     * Returns true if the canonicalized input is a valid integer within the
     * specified range minValue to maxValue.
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. PurchasePage_Quantity). This value is used by
     *         any logging or error handling that is done with respect to the
     *         value passed in.
     * @param  $input The actual user input data to validate.
     * @param  $minValue numeric lowest legal value for input.
     * @param  $maxValue numeric highest legal value for input.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     * 
     * @return true if the canonicalized input is a valid integer or false
     *         otherwise.
     */
    public function isValidInteger($context, $input, $minValue, $maxValue, $allowNull)
    {
        try
        {
            $this->_assertValidInteger($context, $input, $minValue, $maxValue, $allowNull);
        }
        catch (Exception $e)
        {
            return false;
        }
        
        return true;
    }
    
    
    /**
     * Asserts that the canonicalized input is a valid integer within the
     * specified range minValue to maxValue.
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException.
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. PurchasePage_Quantity). This value is used by
     *         any logging or error handling that is done with respect to the
     *         value passed in.
     * @param  $input The actual user input data to validate.
     * @param  $minValue numeric lowest legal value for input.
     * @param  $maxValue numeric highest legal value for input.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     * 
     * @return null.
     * 
     * @throws ValidationException.
     * @throws IntrusionException.
     */
    private function _assertValidInteger($context, $input, $minValue, $maxValue, $allowNull)
    {
        $nvr = new IntegerValidationRule(
            'IntegerValidator', $this->encoder, $minValue, $maxValue
        );
        $nvr->setAllowNull($allowNull);
        
        $nvr->assertValid($context, $input);
        
        return null; 
    }
    
    
    /**
     * Returns true if the canonicalized input is a valid double within the
     * specified range minValue to maxValue.
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. PurchasePage_Quantity). This value is used by
     *         any logging or error handling that is done with respect to the
     *         value passed in.
     * @param  $input The actual user input data to validate.
     * @param  $minValue numeric lowest legal value for input.
     * @param  $maxValue numeric highest legal value for input.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     * 
     * @return true if the canonicalized input is a valid double or false
     *         otherwise.
     */
    public function isValidDouble($context, $input, $minValue, $maxValue, 
        $allowNull)
    {
        try
        {
            $this->_assertValidDouble($context, $input, $minValue, $maxValue, $allowNull);
        }
        catch (Exception $e)
        {
            return false;
        }
        
        return true;
    }
    

    /**
     * Asserts that the canonicalized input is a valid double within the
     * specified range minValue to maxValue.
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException.
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. PurchasePage_Quantity). This value is used by
     *         any logging or error handling that is done with respect to the
     *         value passed in.
     * @param  $input The actual user input data to validate.
     * @param  $minValue numeric lowest legal value for input.
     * @param  $maxValue numeric highest legal value for input.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     * 
     * @return null.
     * 
     * @throws ValidationException.
     * @throws IntrusionException.
     */
    private function _assertValidDouble($context, $input, $minValue, $maxValue, $allowNull)
    {
        $this->_assertValidNumber($context, $input, $minValue, $maxValue, $allowNull);
        
        return null;
    }
    
    
    /**
     * Returns true if input is valid file content.
     * This is a good place to check for max file size, allowed character sets
     * and do virus scans.
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. UploadPage_Avatar). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $input The actual user input data to validate.
     * @param  $maxBytes The maximum number of bytes allowed in a legal file.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     * 
     * @return true if input contains only valid file content or false
     *         otherwise.
     */
    public function isValidFileContent($context, $input, $maxBytes, $allowNull)
    {
        try
        {
            $this->_assertValidFileContent($context, $input, $maxBytes, $allowNull);
        }
        catch(Exception $e)
        {
            return false;
        }

        return true;
    }
    
    
    /**
     * Asserts that the size of the input is less than both the supplied
     * $maxBytes and the AllowedFileUploadSize SecurityConfiguration directive.
     * Invalid input will generate a descriptive ValidationException.
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. UploadPage_Avatar). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $input The actual user input data to validate.
     * @param  $maxBytes The maximum number of bytes allowed in a legal file.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     * 
     * @return null.
     * 
     * @throws ValidationException.
     */
    private function _assertValidFileContent($context, $input, $maxBytes, $allowNull)
    {
        if (! is_string($context)) {
            $context = 'Validate File Content';
        }
        if (! is_string($input) && $input !== null) {
            throw new ValidationException(
                "{$context}: Input required",
                "Input was not a string or NULL: context={$context}",
                $context
            );
        }
        
        if (! is_numeric($maxBytes) || $maxBytes < 0) {
            $this->logger->warning(ESAPILogger::SECURITY, false,
                'assertValidFileContent expected $maxBytes as positive integer. Falling back to AllowedFileUploadSize.'
            );
            $maxBytes = null;
        }

        if ($input === null || $input == '')
        {
            if ($this->allowNull) {
                return null;
            }
            throw new ValidationException(
                "{$context}: Input required",
                "Input required: context={$context}",
                $context
            );
        }
        
        $config = ESAPI::getSecurityConfiguration();     
        $esapiMaxBytes = $config->getAllowedFileUploadSize();
        
        $charEnc = mb_detect_encoding($input);
        $inputLen = mb_strlen($input, $charEnc);
        
        if ($inputLen > $esapiMaxBytes ) {
            throw new ValidationException(
                "{$context}: Invalid file content. Size must not exceed {$esapiMaxBytes} bytes.",
                "Invalid file content. Input ({$inputLen} bytes) exceeds AllowedFileUploadSize ({$esapiMaxBytes} bytes.)",
                 $context
            );
        }
             
        if ($maxBytes !== null && $inputLen > $maxBytes ) {
             throw new ValidationException(
                 "{$context}: Invalid file content. Size must not exceed {$maxBytes} bytes.",
                 "Invalid file content. Input ({$inputLen} bytes) exceeds maximum of ({$esapiMaxBytes} bytes.)",
                 $context
             );
        }
                
        return null;
    }
    
    
    /**
     * Returns true if a file upload has a valid name, path, and content.
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. UploadPage_Avatar). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $filepath The file path of the uploaded file.
     * @param  $filename The filename of the uploaded file
     * @param  $content A string containing the content of the uploaded file.
     * @param  $maxBytes The max number of bytes allowed for a legal file upload.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     * 
     * @return true, if a file upload has a valid name, path, and content or
     *         false otherwise.
     */
    public function isValidFileUpload($context, $filepath, $filename, $content, $maxBytes, $allowNull)
    {
        try
        {
            $this->_assertValidFileUpload(
                $context, $filepath, $filename, $content, $maxBytes, $allowNull
            );
        }
        catch (Exception $e)
        {
            return false;
        }
        
        return true;
    }


    /**
     * Asserts that a file upload has a valid name, path, and content.
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException.
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. UploadPage_Avatar). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $filepath The file path of the uploaded file.
     * @param  $filename The filename of the uploaded file
     * @param  $content A string containing the content of the uploaded file.
     * @param  $maxBytes The max number of bytes allowed for a legal file upload.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     * 
     * @return null.
     * 
     * @throws ValidationException.
     * @throws IntrusionException.
     */
    private function _assertValidFileUpload($context, $filepath, $filename, $content, $maxBytes, $allowNull)
    {
        throw new EnterpriseSecurityException(
            'Method Not implemented',
            'assertValidFileUpload method not implemented'
        );
    }

    
    /**
     * Validate the current HTTP request by comparing parameters, headers and
     * cookies to a predefined whitelist of allowed characters.
     * For the methods to retrieve the whitelists {@see SecurityConfiguration}.
     * 
     * @return true, if the current request is a valid HTTP request or false
     *         otherwise.
     */
    public function isValidHTTPRequest()
    {
        try
        {
            $this->_assertValidHTTPRequest();
        }
        catch (Exception $e)
        {
            return false;
        }
        
        return true;
    }


    /**
     * Asserts that the current HTTP request is valid by comparing parameters,
     * headers and cookies to a predefined whitelist of allowed characters.
     * For the methods to retrieve the whitelists {@see SecurityConfiguration}.
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException.
     * 
     * @throws ValidationException.
     * @throws IntrusionException.
     */
    private function _assertValidHTTPRequest()
    {
        throw new EnterpriseSecurityException(
            'Method Not implemented',
            'assertValidHTTPRequest method not implemented'
        );
    }

    
    /**
     * Returns true if the canonicalized input exactly matches a list item.
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. Contact_Recipient). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $input The value to search for in the supplied list.
     * @param  $list The list to search for the supplied input.
     * 
     * @return true if the canonicalized input exactly matches a list item,
     *         false otherwise.
     */
    public function isValidListItem($context, $input, $list)
    {
        try
        {
            $this->_assertValidListItem($context, $input, $list);
        }
        catch (Exception $e)
        {
            return false;
        }
        
        return true;
    }

    
    /**
     * Asserts that the canonicalized input exactly matches a list item.
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException. 
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. Contact_Recipient). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $input The value to search for in the supplied list.
     * @param  $list array of strings to search for the supplied input.
     * 
     * @return null.
     * 
     * @throws ValidationException.
     * @throws IntrusionException.
     */
    private function _assertValidListItem($context, $input, $list)
    {
        // Some sanity checks first
        if (! is_string($context)) {
            $context = 'ValidListItem';
        }
        if (! is_string($input) && $input !== null) {
            throw new ValidationException(
                "{$context}: Input required",
                "Input was not a string or NULL: context={$context}",
                $context
            );
        }
        if (! is_array($list)) {
            throw new RuntimeException(
                'Validation misconfiguration - assertValidListItem expected an array $list!'
            );
        }

        // strict canonicalization
        $canonical = null;
        try
        {
            $canonical = $this->encoder->canonicalize($input, true);
        }
        catch (EncodingException $e)
        {
            throw new ValidationException(
                $context . ': Invalid input. Encoding problem detected.',
                'An EncodingException was thrown during canonicalization of the input.',
                $context
            );
        }
        
        if (in_array($canonical, $list, true) != true ) {
            throw new ValidationException(
                $context . ': Invalid input. Input was not a valid member of the list.',
                'canonicalized input was not a member of the supplied list.',
                $context
            );
        }
        
        return null;
    }

    
    /**
     * Returns true if the parameters in the current request contain all
     * required parameters and only the optional parameters specified.
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. Request_Params). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $required parameters that are required to be in HTTP request.
     * @param  $optional additional parameters that may be in HTTP request.
     * 
     * @return true if all required parameters are in HTTP request and only
     *         optional parameters in addition.
     *         false if parameters are found in HTTP request that are not in
     *         either set (required or optional), or if any required parameters
     *         are missing from request.
     */
    public function isValidHTTPRequestParameterSet($context, $required, $optional)
    {
        try
        {
            $this->_assertValidHTTPRequestParameterSet($context, $required, $optional);
        }
        catch (Exception $e)
        {
            return false;
        }
        
        return true;
    }


    /**
     * Asserts that the parameters in the current request contain all required
     * parameters and only the optional parameters specified.
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException. 
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. Request_Params). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $required parameters that are required to be in HTTP request.
     * @param  $optional additional parameters that may be in HTTP request.
     * 
     * @return null.
     * 
     * @throws ValidationException.
     * @throws IntrusionException.
     */
    private function _assertValidHTTPRequestParameterSet($context, $required, $optional)
    {
        throw new EnterpriseSecurityException(
            'Method Not implemented',
            'assertValidHTTPRequestParameterSet method not implemented'
        );
    }

    
    /**
     * Returns true if the canonicalized input contains no more than the number
     * of valid printable ASCII characters specified.
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. ASCIIArt_Submission). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $input The actual user input data to validate.
     * @param  $maxLength The maximum number of canonicalized ascii characters
     *         allowed in a legal input.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     * 
     * @return true if the canonicalized input contains no more than the number
     *         of valid printable ASCII characters specified by $maxLength,
     *         false otherwise.
     */
    public function isValidPrintable($context, $input, $maxLength, $allowNull)
    {
        try
        {
            $this->_assertValidPrintable($context, $input, $maxLength, $allowNull);
        }
        catch (Exception $e)
        {
            return false;
        }
        
        return true;
    }


    /**
     * Asserts that the canonicalized input contains no more than the number of
     * valid printable ASCII characters specified.
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException. 
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. ASCIIArt_Submission). This value is used by any
     *         logging or error handling that is done with respect to the value
     *         passed in.
     * @param  $input The actual user input data to validate.
     * @param  $maxLength The maximum number of canonicalized ascii characters
     *         allowed in a legal input.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     * 
     * @return null.
     * 
     * @throws ValidationException.
     * @throws IntrusionException.
     */
    private function _assertValidPrintable($context, $input, $maxLength, $allowNull)
    {
        $c = '\x20\x21\x22\x23\x24\x25\x26\x27' .
             '\x28\x29\x2a\x2b\x2c\x2d\x2e\x2f' .
			 '0-9\x3a\x3b\x3c\x3d\x3e\x3f\x40' .
			 'A-Z\x5b\x5c\x5d\x5e\x5f\x60' .
			 'a-z\x7b\x7c\x7d\x7e';
        $pattern = "^[{$c}]*$";
        
        $this->_assertValidInput($context, $input, $pattern, $maxLength, $allowNull);
        
        return null;
    }

    
    /**
     * Returns true if the canonicalized input is a valid redirect location as
     * defined in "ESAPI.xml".
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. IncludeFile). This value is used by any logging
     *         or error handling that is done with respect to the value passed
     *         in.
     * @param  $input The actual user input data to validate.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     * 
     * @return true if the canonicalized is a valid redirect location as defined
     *         in "ESAPI.xml", false otherwise.
     */
    public function isValidRedirectLocation($context, $input, $allowNull)
    {
        try
        {
            $this->_assertValidRedirectLocation($context, $input, $allowNull);
        }
        catch (Exception $e)
        {
            return false;
        }
        
        return true;
    }

    
    /**
     * Asserts that the canonicalized input is a valid redirect location as
     * defined in "ESAPI.xml".
     * Invalid input will generate a descriptive ValidationException, and input
     * that is clearly an attack will generate a descriptive IntrusionException.
     * 
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. IncludeFile). This value is used by any logging
     *         or error handling that is done with respect to the value passed
     *         in.
     * @param  $input The actual user input data to validate.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     * 
     * @return null.
     * 
     * @throws ValidationException.
     * @throws IntrusionException.
     */
    private function _assertValidRedirectLocation($context, $input, $allowNull)
    {
        throw new EnterpriseSecurityException(
            'Method Not implemented',
            'assertValidRedirectLocation method not implemented'
        );
    }
    
	
	/**
	 * Reads from an input stream until end-of-line or a maximum number of
	 * characters. This method protects against the inherent denial of service
	 * attack in reading until the end of a line. If an attacker doesn't ever
	 * send a newline character, then a normal input stream reader will read
	 * until all memory is exhausted and the platform throws an OutOfMemoryError
	 * and probably terminates.
	 * 
	 * @param  $inputStream The InputStream from which to read data
	 * @param  $maxLength Maximum number of characters allowed to be read in
	 *         per line
	 * 
	 * @return a String containing the current line of inputStream
	 * 
	 * @throws ValidationException
	 */
	function safeReadLine($inputStream, $maxLength)
	{
        throw new EnterpriseSecurityException(
            'Method Not implemented',
            'safeReadLine method not implemented'
        );
    }

}
