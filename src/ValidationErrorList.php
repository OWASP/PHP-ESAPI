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
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   SVN: $Id$
 * @link      http://www.owasp.org/index.php/ESAPI
 */

require_once dirname(__FILE__).'/errors/ValidationException.php';

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
 * <samp>
 * ValidationErrorList() errorList = new ValidationErrorList();.
 * String name  = getValidInput("Name", form.getName(), "SomeESAPIRegExName1", 255, false, errorList);
 * String address = getValidInput("Address", form.getAddress(), "SomeESAPIRegExName2", 255, false, errorList);
 * Integer weight = getValidInteger("Weight", form.getWeight(), 1, 1000000000, false, errorList);
 * Integer sortOrder = getValidInteger("Sort Order", form.getSortOrder(), -100000, +100000, false, errorList);
 * request.setAttribute( "ERROR_LIST", errorList );
 * </samp>
 * 
 * The at your view layer you would be able to retrieve all
 * of your error messages via a helper function like:
 * 
 * <samp>
 * public static ValidationErrorList getErrors() {          
 *     HttpServletRequest request = ESAPI.httpUtilities().getCurrentRequest();
 *     ValidationErrorList errors = new ValidationErrorList();
 *     if (request.getAttribute(Constants.ERROR_LIST) != null) {
 *        errors = (ValidationErrorList)request.getAttribute("ERROR_LIST");
 *     }
 * 	   return errors;
 * }
 * </samp>
 * 
 * You can list all errors like:
 * 
 * <samp>
 * <%
 *      for (Object vo : errorList.errors()) {
 *         ValidationException ve = (ValidationException)vo;
 * %>
 * <%= ESAPI.encoder().encodeForHTML(ve.getMessage()) %><br/>
 * <%
 *     }
 * %>
 * </samp>
 * 
 * And even check if a specific UI component is in error via calls like:
 * 
 * <samp>
 * ValidationException e = errorList.getError("Name");
 * </samp>
 * 
 * PHP version 5.2
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    Jeff Williams <jeff.williams@aspectsecurity.com>
 * @author    Andrew van der Stock <vanderaj@owasp.org>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   Release: @package_version@
 * @link      http://www.owasp.org/index.php/ESAPI
 */
class ValidationErrorList 
{

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
