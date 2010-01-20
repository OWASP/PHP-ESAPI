<?php

/**
 * OWASP Enterprise Security API (ESAPI)
 * 
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * <a href="http://www.owasp.org/index.php/ESAPI">http://www.owasp.org/index.php/ESAPI</a>.
 *
 * Copyright (c) 2007 - 2009 The OWASP Foundation
 * 
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 * 
 * @author Johannes B. Ullrich
 * @created 2008
 * @since 1.4
 * @package org.owasp.esapi.reference
 */

require_once dirname ( __FILE__ ) . '/../Validator.php';
require_once dirname ( __FILE__ ) . '/../ValidationRule.php';
require_once dirname ( __FILE__ ) . '/validation/StringValidationRule.php';
require_once dirname ( __FILE__ ) . '/validation/CreditCardValidationRule.php';
require_once dirname ( __FILE__ ) . '/validation/HTMLValidationRule.php';
require_once dirname ( __FILE__ ) . '/validation/NumberValidationRule.php';
require_once dirname ( __FILE__ ) . '/validation/IntegerValidationRule.php';
require_once dirname ( __FILE__ ) . '/validation/DateValidationRule.php';

class DefaultValidator implements Validator {
	
	private $rules = null;
	private $encoder = null;
	private $fileValidator = null;
	const MAX_PARAMETER_NAME_LENGTH = 100;
	const MAX_PARAMETER_VALUE_LENGTH = 65535;
	
	
	/**
	 * Returns canonicalized and validated input as a String. Invalid input will generate a descriptive ValidationException, 
	 * and input that is clearly an attack will generate a descriptive IntrusionException.  Instead of
	 * throwing a ValidationException on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual user input data to validate.
	 * @param type 
	 * 		The regular expression name that maps to the actual regular expression from "ESAPI.properties".
	 * @param maxLength 
	 * 		The maximum post-canonicalized String length allowed.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @return The canonicalized user input.
	 * 
	 * @throws IntrusionException
	 */
	function getValidInput($context, $input, $type, $maxLength, $allowNull, $errorList = null) {
		global $ESAPI;
		$config = ESAPI::getSecurityConfiguration();
		$rvr = new StringValidationRule ( $type, $this->encoder );
		$p=$config->getValidationPattern($type);
		if ($p != null) {
			$rvr->addWhitelistPattern ( $p );
		} else {
			$rvr->addWhitelistPattern ( $type );
		}
		$rvr->setMaximumLength( $maxLength );
		$rvr->setAllowNull( $allowNull );
		return $rvr->sanitize( $context, $input );
	}
	

	
	/**
	 * Returns a valid date as a Date. Invalid input will generate a descriptive ValidationException and store it inside of 
	 * the errorList argument, and input that is clearly an attack will generate a descriptive IntrusionException.  Instead of
	 * throwing a ValidationException on error, this variant will store the exception inside of the ValidationErrorList.
	 * 
	 * @param context 
	 * 		A descriptive name of the parameter that you are validating (e.g., LoginPage_UsernameField). This value is used by any logging or error handling that is done with respect to the value passed in.
	 * @param input 
	 * 		The actual user input data to validate.
	 * @param format 
	 * 		Required formatting of date inputted.
	 * @param allowNull 
	 * 		If allowNull is true then an input that is NULL or an empty string will be legal. If allowNull is false then NULL or an empty String will throw a ValidationException.
	 * @param errorList 
	 * 		If validation is in error, resulting error will be stored in the errorList by context
	 * 
	 * @return A valid date as a Date
	 * 
	 * @throws IntrusionException
	 */
	function getValidDate($context, $input, $format, $allowNull, $errorList = null) {
		$dvr = new DateValidationRule("SimpleDate", $this->encoder );
		$dvr->setAllowNull ( $allowNull );
		$dvr->setDateFormat ( $format );
		return $dvr->getValid( $context, $input);
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