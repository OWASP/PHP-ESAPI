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
 * @author    Johannes B. Ullrich <jullrich@sans.edu>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */


/**
 * Require the Validator and ValidationRule interfaces and the various
 * ValidationRule implementations.
 */
require_once dirname ( __FILE__ ) . '/../Validator.php';
require_once dirname ( __FILE__ ) . '/../ValidationRule.php';
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
 * @author    Johannes B. Ullrich <jullrich@sans.edu>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */
class DefaultValidator implements Validator {

    private $rules = null;
    private $encoder = null;
    private $fileValidator = null;
    const MAX_PARAMETER_NAME_LENGTH = 100;
    const MAX_PARAMETER_VALUE_LENGTH = 65535;


    /**
     * Returns true if input is valid according to the specified type. The type
     * parameter must be the name of a defined type in the ESAPI configuration
     * or a valid regular expression. This method does not perform
     * canonicalization of the input.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. LoginPage_UsernameField). This value is used by
     *         any logging or error handling that is done with respect to the
     *         value passed in.
     * @param  $input The actual user input data to validate.
     * @param  $type The regular expression name that maps to the actual regular
     *         expression from "ESAPI.xml".
     * @param  $maxLength The maximum string length allowed.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will not be valid.
     *
     * @return true, if the input is valid based on the rules set by 'type' or
     *         false otherwise.
     */
    function isValidInput($context, $input, $type, $maxLength, $allowNull)
    {
        try
        {
            $this->assertValidInput(
                $context, $input, $type, $maxLength, $allowNull
            );
            return true;
        }
        catch ( Exception $e )
        {
            return false;
        }

    }


    /**
     * Asserts that the input is valid according to the supplied type.
     * Invalid input will generate a descriptive ValidationException.  This
     * method does not perform canonicalization of the input.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g. LoginPage_UsernameField). This value is used by
     *         any logging or error handling that is done with respect to the
     *         value passed in.
     * @param  $input The actual user input data to validate.
     * @param  $type The regular expression name that maps to the actual regular
     *         expression from "ESAPI.xml".
     * @param  $maxLength The maximum string length allowed.
     * @param  $allowNull If allowNull is true then an input that is NULL or an
     *         empty string will be legal. If allowNull is false then NULL or an
     *         empty String will throw a ValidationException.
     *
     * @return null.
     *
     * @throws ValidationException.
     */
    function assertValidInput($context, $input, $type, $maxLength, $allowNull)
    {
        global $ESAPI;
        $config = ESAPI::getSecurityConfiguration();
        $validationRule = new StringValidationRule ($type, $this->encoder);

        $pattern = $config->getValidationPattern($type);
        if ($pattern != null) {
            $validationRule->addWhitelistPattern ($pattern);
        } else {
            $validationRule->addWhitelistPattern ($type);
        }

        $validationRule->setMaximumLength($maxLength);
        $validationRule->setAllowNull($allowNull);

        return $validationRule->assertValid($context, $input);
    }


    /**
     * Returns true if input is a valid date according to the specified date
     * format or false otherwise. This method canonicalizes non-null and
     * non-empty inputs before performing validation.
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
     * @return true if input is a valid date according to the format specified
     *         by $format, or false otherwise.
     */
    function isValidDate($context, $input, $format, $allowNull)
    {
        try
        {
            $this->assertValidDate ($context, $input, $format, $allowNull);
            return true;
        }
        catch ( Exception $e )
        {
            return false;
        }
    }


    /**
     * Asserts that the input is a valid date according to the supplied format.
     * Invalid input will throw a descriptive ValidationException or, in the
     * case of inputs which are found to contain mixed or double encoding, an
     * IntrusionException. This method canonicalizes non-null and non-empty
     * inputs before performing validation.
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
     * @throws ValidationException or IntrusionException.
     */
    function assertValidDate($context, $input, $format, $allowNull)
    {
        $dvr = new DateValidationRule("SimpleDate", $this->encoder, $format);
        $dvr->setAllowNull($allowNull);

        return $dvr->assertValid( $context, $input);
    }


	/**
	 * Returns true if input is "safe" HTML. Implementors should reference the OWASP AntiSamy project for ideas
	 * on how to do HTML validation in a whitelist way, as this is an extremely difficult problem.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual user input data to validate.
	 * @param maxLength 
	 * 		The maximum post-canonicalized String length allowed.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * 
	 * @return true, if input is valid safe HTML
	 * 
	 * @throws IntrusionException
	 */
	function isValidSafeHTML($context, $input, $maxLength, $allowNull) {
		try {
			$this->getValidSafeHTML ( $context, $input, $maxLength, $allowNull );
			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}
	
	/**
	 * Returns canonicalized and validated "safe" HTML. Implementors should reference the OWASP AntiSamy project for ideas
	 * on how to do HTML validation in a whitelist way, as this is an extremely difficult problem. Instead of
	 * throwing a ValidationException on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual user input data to validate.
	 * @param maxLength 
	 * 		The maximum post-canonicalized String length allowed.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @return Valid safe HTML
	 * 
	 * @throws IntrusionException
	 */
	function getValidSafeHTML($context, $input, $maxLength, $allowNull,$error=null) {
		try {
		      $hvr=new HTMLValidationRule("safehtml",$this->encoder);
		      $hvr->setMaximumLength($maxLength);
		      $hvr->setAllowNull($allowNull);
		      return $hvr->getValid($context,$input);
		} catch (Exception $e) {
		  	
			$errors->addError($context,$e);
		}
		return $input;
	}
	
	/**
	 * Returns true if input is a valid credit card. Maxlength is mandated by valid credit card type.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual user input data to validate.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * 
	 * @return true, if input is a valid credit card number
	 * 
	 * @throws IntrusionException
	 */
	function isValidCreditCard($context, $input, $allowNull) {
		try {
			$this->getValidCreditCard($context,$input,$allowNull);
			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}
	
	/**
	 * Returns a canonicalized and validated credit card number as a String. Invalid input
	 * will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException 
	 * on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @return A valid credit card number
	 * 
	 * @throws IntrusionException
	 */
	function getValidCreditCard($context, $input, $allowNull, $errorList = null) {
		$ccvr=new CreditCardValidationRule('CreditCard',$this->encoder);
		$ccvr->setAllowNull($allowNull);
		return $ccvr->getValid($context,$input);
	}
	
	/**
	 * Returns true if input is a valid directory path.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * 
	 * @return true, if input is a valid directory path
	 * 
	 * @throws IntrusionException 
	 */
	function isValidDirectoryPath($context, $input, $allowNull) {
		try {
			$this->getValidDirectoryPath($context,$input, $allowNull);
			return true;
		} catch ( Exception $e) {
			return false;
		}
	}
	
	/**
	 * Returns a canonicalized and validated directory path as a String. Invalid input
	 * will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException 
	 * on error, this variant will store the exception inside of the ValidationErrorList.
	 *  
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @return A valid directory path
	 * 
	 * @throws IntrusionException
	 */
	function getValidDirectoryPath($context, $input, $allowNull, $errorList = null) {
		throw new EnterpriseSecurityException ( "Method Not implemented" );
	}
	
	/**
	 * Returns true if input is a valid file name.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * 
	 * @return true, if input is a valid file name
	 * 
	 * @throws IntrusionException
	 */
	function isValidFileName($context, $input, $allowNull) {
	try {
		$this->getValidFileName( $context, $input, $allowNull );
			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}
	

	/**
	 * Returns a canonicalized and validated file name as a String. Implementors should check for allowed file extensions here, as well as allowed file name characters, as declared in "ESAPI.properties".  Invalid input
	 * will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException 
	 * on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @return A valid file name
	 * 
	 * @throws IntrusionException
	 */
	function getValidFileName($context, $input, $allowNull, $errorList = null) {
		throw new EnterpriseSecurityException ( "Method Not implemented" );
	}
	
	/**
	 * Returns true if input is a valid number within the range of minValue to maxValue.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param minValue 
	 * 		Lowest legal value for input.
	 * @param maxValue 
	 * 		Highest legal value for input.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * 
	 * @return true, if input is a valid number
	 * 
	 * @throws IntrusionException
	 */
	function isValidNumber($context, $input, $minValue, $maxValue, $allowNull) {
		try {
			$this->getValidNumber ( $context, $input, $minValue, $maxValue, $allowNull );
			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}
	

	/**
	 * Returns a validated number as a double within the range of minValue to maxValue. Invalid input
	 * will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException 
	 * on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param minValue 
	 * 		Lowest legal value for input.
	 * @param maxValue 
	 * 		Highest legal value for input.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @return A validated number as a double.
	 * 
	 * @throws IntrusionException
	 */

	function getValidNumber($context, $input, $minValue, $maxValue, $allowNull, $errorList=null) {
		$nvr=new NumberValidationRule('number',$this->encoder,$minValue,$maxValue);
		$nvr->setAllowNull($allowNull);
		return $nvr->getValid($context,$input);
	}
	

	/**
	 * Returns true if input is a valid integer within the range of minValue to maxValue.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param minValue 
	 * 		Lowest legal value for input.
	 * @param maxValue 
	 * 		Highest legal value for input.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * 
	 * @return true, if input is a valid integer
	 * 
	 * @throws IntrusionException
	 */
	function isValidInteger($context, $input, $minValue, $maxValue, $allowNull) {
	try {
			$this->getValidInteger ( $context, $input, $minValue, $maxValue, $allowNull );
			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}
	
	/**
	 * Returns a validated integer. Invalid input
	 * will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException 
	 * on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param minValue 
	 * 		Lowest legal value for input.
	 * @param maxValue 
	 * 		Highest legal value for input.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @return A validated number as an integer.
	 * 
	 * @throws IntrusionException
	 */
	function getValidInteger($context, $input, $minValue, $maxValue, $allowNull, $errorList = null) {
		$nvr=new IntegerValidationRule('integer',$this->encoder,$minValue,$maxValue);
		$nvr->setAllowNull($allowNull);
		return $nvr->getValid($context,$input);
	}
	
	/**
	 * Returns true if input is a valid double within the range of minValue to maxValue.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param minValue 
	 * 		Lowest legal value for input.
	 * @param maxValue 
	 * 		Highest legal value for input.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * 
	 * @return true, if input is a valid double.
	 * 
	 * @throws IntrusionException
	 * 
	 */
	function isValidDouble($context, $input, $minValue, $maxValue, $allowNull) {
	try {
			$this->getValidDouble ( $context, $input, $minValue, $maxValue, $allowNull );
			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}
	

	/**
	 * Returns a validated real number as a double. Invalid input
	 * will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException 
	 * on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param minValue 
	 * 		Lowest legal value for input.
	 * @param maxValue 
	 * 		Highest legal value for input.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @return A validated real number as a double.
	 * 
	 * @throws IntrusionException
	 */
	function getValidDouble($context, $input, $minValue, $maxValue, $allowNull, $errorList = null) {
		throw new EnterpriseSecurityException ( "Method Not implemented" );
	}
	
	/**
	 * Returns true if input is valid file content.  This is a good place to check for max file size, allowed character sets, and do virus scans.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param maxBytes 
	 * 		The maximum number of bytes allowed in a legal file.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * 
	 * @return true, if input contains valid file content.
	 * 
	 * @throws IntrusionException
	 */
	function isValidFileContent($context, $input, $maxBytes, $allowNull) {
		try {
        	$this->getValidFileContent( $context, $input, $maxBytes, $allowNull);
            return true;
        } catch( Exception $e ) {
            return false;
        }		
	}
	
	/**
	 * Returns validated file content as a byte array. This is a good place to check for max file size, allowed character sets, and do virus scans.  Invalid input
	 * will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException 
	 * on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual input data to validate.
	 * @param maxBytes 
	 * 		The maximum number of bytes allowed in a legal file.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context.
	 * 
	 * @return A byte array containing valid file content.
	 * 
	 * @throws IntrusionException
	 */
	function getValidFileContent($context, $input, $maxBytes, $allowNull, $errorList = null) {
		if (strlen($input)==0) {
            if ($this->allowNull) return null;
            throw new ValidationException( $context.": Input required", "Input required: context=".$context.", input=".$input, $context );
        }
        $config = ESAPI::getSecurityConfiguration();     
        $esapiMaxBytes = $config->getAllowedFileUploadSize();
        if (strlen($input) > $esapiMaxBytes ) throw new ValidationException( $context.": Invalid file content can not exceed ".$esapiMaxBytes." bytes",
 			"Exceeded ESAPI max length", $context );
        if (strlen($input) > $maxBytes ) throw new ValidationException( $context.": Invalid file content can not exceed ".$maxBytes." bytes", "Exceeded maxBytes ( ".strlen($input).")", $context );
                
        return $input;
		
	}
	
	/**
	 * Returns true if a file upload has a valid name, path, and content.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param filepath 
	 * 		The file path of the uploaded file.
	 * @param filename 
	 * 		The filename of the uploaded file
	 * @param content 
	 * 		A byte array containing the content of the uploaded file.
	 * @param maxBytes 
	 * 		The max number of bytes allowed for a legal file upload.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * 
	 * @return true, if a file upload has a valid name, path, and content.
	 * 
	 * @throws IntrusionException
	 */
	function isValidFileUpload($context, $filepath, $filename, $content, $maxBytes, $allowNull) {

	try {
			$this->assertValidFileUpload( $context, $filepath, $filename, $content, $maxBytes, $allowNull );
			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}
	

	/**
	 * Validates the filepath, filename, and content of a file. Invalid input
	 * will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException 
	 * on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param filepath 
	 * 		The file path of the uploaded file.
	 * @param filename 
	 * 		The filename of the uploaded file
	 * @param content 
	 * 		A byte array containing the content of the uploaded file.
	 * @param maxBytes 
	 * 		The max number of bytes allowed for a legal file upload.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @throws IntrusionException
	 */
	function assertValidFileUpload($context, $filepath, $filename, $content, $maxBytes, $allowNull, $errorList = null) {
		throw new EnterpriseSecurityException ( "Method Not implemented" );
	}
	
	/**
	 * Validate the current HTTP request by comparing parameters, headers, and cookies to a predefined whitelist of allowed
	 * characters. See the SecurityConfiguration class for the methods to retrieve the whitelists.
	 * 
	 * @return true, if is a valid HTTP request
	 * 
	 * @throws IntrusionException
	 */
	function isValidHTTPRequest() {
	try {
			$this->getValidHTTPRequest ();
			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}
	

	/**
	 * Validates the current HTTP request by comparing parameters, headers, and cookies to a predefined whitelist of allowed
	 * characters. Invalid input will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException.
	 * 
	 * @throws ValidationException
	 * @throws IntrusionException
	 */
	function assertIsValidHTTPRequest() {
		throw new EnterpriseSecurityException ( "Method Not implemented" );
	}
	
	/**
	 * Returns true if input is a valid list item.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The value to search 'list' for.
	 * @param list 
	 * 		The list to search for 'input'.
	 * 
	 * @return true, if 'input' was found in 'list'.
	 * 
	 * @throws IntrusionException
	 */
	function isValidListItem($context, $input, $list) {

		try {
			return $this->getValidListItem ( $context, $input, $list );
		} catch ( Exception $e ) {
			return false;
		}
	}
	
	/**
	 * Returns the list item that exactly matches the canonicalized input. Invalid or non-matching input
	 * will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException 
	 * on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The value to search 'list' for.
	 * @param list 
	 * 		The list to search for 'input'.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @return The list item that exactly matches the canonicalized input.
	 * 
	 * @throws IntrusionException
	 */
	function getValidListItem($context, $input, $list, $errorList = null) {
		 if ( in_array($input,$list) ) {
		   return $input;
		 }
		 return false;
	}
	
	/**
	 * Returns true if the parameters in the current request contain all required parameters and only optional ones in addition.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param required 
	 * 		parameters that are required to be in HTTP request 
	 * @param optional 
	 * 		additional parameters that may be in HTTP request
	 * 
	 * @return true, if all required parameters are in HTTP request and only optional parameters in addition.  Returns false if parameters are found in HTTP request that are not in either set (required or optional), or if any required parameters are missing from request.
	 * 
	 * @throws IntrusionException
	 */
	function isValidHTTPRequestParameterSet($context, $required, $optional) {
	try {
			$this->getValidHTTPRequestParameterSet( $context, $required, $optional );
			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}
	

	/**
	 * Validates that the parameters in the current request contain all required parameters and only optional ones in
	 * addition. Invalid input will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException on error, 
	 * this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param required 
	 * 		parameters that are required to be in HTTP request
	 * @param optional 
	 * 		additional parameters that may be in HTTP request
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @throws IntrusionException
	 */
	function assertIsValidHTTPRequestParameterSet($context, $required, $optional, $errorList = null) {
		throw new EnterpriseSecurityException ( "Method Not implemented" );
	}
	
	/**
	 * Returns true if input contains only valid printable ASCII characters.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		data to be checked for validity
	 * @param maxLength 
	 * 		Maximum number of bytes stored in 'input'
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * 
	 * @return true, if 'input' is less than maxLength and contains only valid, printable characters
	 * 
	 * @throws IntrusionException
	 */
	function isValidPrintable($context, $input, $maxLength, $allowNull) {
	try {
			$this->getValidPrintable ( $context, $input, $maxLength, $allowNull );
			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}
	

	/**
	 * Returns canonicalized and validated printable characters as a byte array. Invalid input will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException on error, 
	 * this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		data to be returned as valid and printable
	 * @param maxLength 
	 * 		Maximum number of bytes stored in 'input'
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @return a byte array containing only printable characters, made up of data from 'input'
	 * 
	 * @throws IntrusionException
	 */
	function getValidPrintable($context, $input, $maxLength, $allowNull, $errorList = null) {
		throw new EnterpriseSecurityException ( "Method Not implemented" );
	}
	
	/**
	 * Returns true if input is a valid redirect location, as defined by "ESAPI.properties".
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		redirect location to be checked for validity, according to rules set in "ESAPI.properties"
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * 
	 * @return true, if 'input' is a valid redirect location, as defined by "ESAPI.properties", false otherwise.
	 * 
	 * @throws IntrusionException
	 */
	function isValidRedirectLocation($context, $input, $allowNull) {
	try {
			$this->getValidRedirectLocation ( $context, $input, $allowNull );
			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}
	
	/**
	 * Returns a canonicalized and validated redirect location as a String. Invalid input will generate a descriptive ValidationException, and input that is clearly an attack
	 * will generate a descriptive IntrusionException. Instead of throwing a ValidationException 
	 * on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		redirect location to be returned as valid, according to encoding rules set in "ESAPI.properties"
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @return A canonicalized and validated redirect location, as defined in "ESAPI.properties"
	 * 
	 * @throws IntrusionException
	 */
	function getValidRedirectLocation($context, $input, $allowNull, $errorList = null) {
		throw new EnterpriseSecurityException ( "Method Not implemented" );
	}
	
	/**
	 * Reads from an input stream until end-of-line or a maximum number of
	 * characters. This method protects against the inherent denial of service
	 * attack in reading until the end of a line. If an attacker doesn't ever
	 * send a newline character, then a normal input stream reader will read
	 * until all memory is exhausted and the platform throws an OutOfMemoryError
	 * and probably terminates.
	 * 
	 * @param inputStream 
	 * 		The InputStream from which to read data
	 * @param maxLength 
	 * 		Maximum characters allowed to be read in per line
	 * 
	 * @return a String containing the current line of inputStream
	 * 
	 * @throws ValidationException
	 */
	function safeReadLine($inputStream, $maxLength) {
		throw new EnterpriseSecurityException ( "Method Not implemented" );
	}

}
?>