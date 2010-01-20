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
 * @author 
 * @created 2008
 * @since 1.4
 * @package org.owasp.esapi
 */

require_once dirname(__FILE__).'/errors/IntrusionException.php';
require_once dirname(__FILE__).'/errors/ValidationException.php';

/**
 * The Validator interface defines a set of methods for canonicalizing and
 * validating untrusted input. Implementors should feel free to extend this
 * interface to accommodate their own data formats. Rather than throw exceptions,
 * this interface returns boolean results because not all validation problems
 * are security issues. Boolean returns allow developers to handle both valid
 * and invalid results more cleanly than exceptions.
 * <P>
 * <img src="doc-files/Validator.jpg">
 * <P>
 * Implementations must adopt a "whitelist" approach to validation where a
 * specific pattern or character set is matched. "Blacklist" approaches that
 * attempt to identify the invalid or disallowed characters are much more likely
 * to allow a bypass with encoding or other tricks.
 * 
 * @author 
 * @since 1.4
 */
interface Validator {


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
	function getValidInput($context, $input, $type, $maxLength, $allowNull, $errorList = null);
	
	
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
	function getValidDate($context, $input, $format, $allowNull, $errorList = null);	
	
	
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
	function getValidSafeHTML($context, $input, $maxLength, $allowNull, $errorList=null);

	
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
	function getValidCreditCard($context, $input, $allowNull, $errorList = null);
	
	
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
	function getValidDirectoryPath($context, $input, $allowNull, $errorList = null);
	
	
	
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
	function getValidFileName($context, $input, $allowNull, $errorList  = null);
		

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
	function getValidNumber($context, $input, $minValue, $maxValue, $allowNull, $errorList=null);

	
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
	function getValidInteger($context, $input, $minValue, $maxValue, $allowNull, $errorList = null);
		

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
	function getValidDouble($context, $input, $minValue, $maxValue, $allowNull, $errorList = null);


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
	function getValidFileContent($context, $input, $maxBytes, $allowNull, $errorList = null);



	

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
	function getValidListItem($context, $input, $list, $errorList = null);
	


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
	function getValidPrintable($context, $input, $maxLength, $allowNull, $errorList = null);



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
	function getValidRedirectLocation($context, $input, $allowNull, $errorList = null);
	
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
	function safeReadLine($inputStream, $maxLength);

}

