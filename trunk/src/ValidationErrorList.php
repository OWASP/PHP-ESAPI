<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 * 
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * <a href="http://www.owasp.org/index.php/ESAPI">http://www.owasp.org/index.php/ESAPI</a>.
 *
 * Copyright (c) 2007 - 2008 The OWASP Foundation
 * 
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 * 
 * @author 
 * @created 2008
 * @since 1.4
 * @package org.owasp.esapi
 */

require_once("errors/ValidationException.php");

/**
 * The ValidationErrorList class defines a well-formed collection of 
 * ValidationExceptions so that groups of validation functions can be 
 * called in a non-blocking fashion.
 * 
 * <P>
 * <img src="doc-files/Validator.jpg">
 * <P>
 * 
 * To use the ValidationErrorList to execute groups of validation 
 * attempts, your controller code would look something like:
 * 
 * <PRE>
 * ValidationErrorList() errorList = new ValidationErrorList();.
 * String name  = getValidInput("Name", form.getName(), "SomeESAPIRegExName1", 255, false, errorList);
 * String address = getValidInput("Address", form.getAddress(), "SomeESAPIRegExName2", 255, false, errorList);
 * Integer weight = getValidInteger("Weight", form.getWeight(), 1, 1000000000, false, errorList);
 * Integer sortOrder = getValidInteger("Sort Order", form.getSortOrder(), -100000, +100000, false, errorList);
 * request.setAttribute( "ERROR_LIST", errorList );
 * </PRE>
 * 
 * The at your view layer you would be able to retrieve all
 * of your error messages via a helper function like:
 * 
 * <PRE>
 * public static ValidationErrorList getErrors() {          
 *     HttpServletRequest request = ESAPI.httpUtilities().getCurrentRequest();
 *     ValidationErrorList errors = new ValidationErrorList();
 *     if (request.getAttribute(Constants.ERROR_LIST) != null) {
 *        errors = (ValidationErrorList)request.getAttribute("ERROR_LIST");
 *     }
 * 	   return errors;
 * }
 * </PRE>
 * 
 * You can list all errors like:
 * 
 * <PRE>
 * <%
 *      for (Object vo : errorList.errors()) {
 *         ValidationException ve = (ValidationException)vo;
 * %>
 * <%= ESAPI.encoder().encodeForHTML(ve.getMessage()) %><br/>
 * <%
 *     }
 * %>
 * </PRE>
 * 
 * And even check if a specific UI component is in error via calls like:
 * 
 * <PRE>
 * ValidationException e = errorList.getError("Name");
 * </PRE>
 * 
 * @author 
 * @since 1.4
 */
class ValidationErrorList {

	/**
	 * Error list of ValidationException's
	 */
	// private HashMap errorList = new HashMap();

	/**
	 * Adds a new error to list with a unique named context.
	 * No action taken if either element is null. 
	 * Existing contexts will be overwritten.
	 * 
	 * @param context unique named context for this ValidationErrorList
	 * @param ve
	 */
	public function addError($context, $ve) {
//		if (getError(context) != null) throw new RuntimeException("Context (" + context + ") already exists, programmer error");
//		
//		if ((context != null) && (ve != null)) {
//			errorList.put(context, ve);
//		}
	}

	/**
	 * Returns list of ValidationException, or empty list of no errors exist.
	 * 
	 * @return List
	 */
	public function errors() {
//		return new ArrayList( errorList.values() );
	}

	/**
	 * Retrieves ValidationException for given context if one exists.
	 * 
	 * @param context unique name for each error
	 * @return ValidationException or null for given context
	 */
	public function getError($context) {
//		if (context == null) return null;		
//		return (ValidationException)errorList.get(context);
	}

	/**
	 * Returns true if no error are present.
	 * 
	 * @return boolean
	 */
	public function isEmpty() {
//		return errorList.isEmpty();
	}
	
	/**
	 * Returns the numbers of errors present.
	 * 
	 * @return boolean
	 */
	public function size() {
//		return errorList.size();
	}
}
