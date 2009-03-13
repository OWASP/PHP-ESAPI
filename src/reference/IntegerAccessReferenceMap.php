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
 * @package org.owasp.esapi.reference
 */

require_once('../src/AccessReferenceMap.php');

class IntegerAccessReferenceMap implements AccessReferenceMap {
	
	/**
	 * Get an iterator through the direct object references. No guarantee is made as 
	 * to the order of items returned.
	 * 
	 * @return the iterator
	 */
	function iterator()
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}

	/**
	 * Get a safe indirect reference to use in place of a potentially sensitive
	 * direct object reference. Developers should use this call when building
	 * URL's, form fields, hidden fields, etc... to help protect their private
	 * implementation information.
	 * 
	 * @param directReference
	 * 		the direct reference
	 * 
	 * @return 
	 * 		the indirect reference
	 */
	function getIndirectReference($directReference)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}

	/**
	 * Get the original direct object reference from an indirect reference.
	 * Developers should use this when they get an indirect reference from a
	 * request to translate it back into the real direct reference. If an
	 * invalid indirect reference is requested, then an AccessControlException is
	 * thrown.
	 * 
	 * @param indirectReference
	 * 		the indirect reference
	 * 
	 * @return 
	 * 		the direct reference
	 * 
	 * @throws AccessControlException 
	 * 		if no direct reference exists for the specified indirect reference
	 */
	function getDirectReference($indirectReference)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}

	/**
	 * Adds a direct reference to the AccessReferenceMap, then generates and returns 
	 * an associated indirect reference.
	 *  
	 * @param direct 
	 * 		the direct reference
	 * 
	 * @return 
	 * 		the corresponding indirect reference
	 */
	function addDirectReference($direct)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}
	
	/**
	 * Removes a direct reference and its associated indirect reference from the AccessReferenceMap.
	 * 
	 * @param direct 
	 * 		the direct reference to remove
	 * 
	 * @return 
	 * 		the corresponding indirect reference
	 * 
	 * @throws AccessControlException
	 */
	function removeDirectReference($direct)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}

	/**
	 * Updates the access reference map with a new set of direct references, maintaining
	 * any existing indirect references associated with items that are in the new list.
	 * New indirect references could be generated every time, but that
	 * might mess up anything that previously used an indirect reference, such
	 * as a URL parameter. 
	 * 
	 * @param directReferences
	 * 		a Set of direct references to add
	 */
	function update($directReferences)
	{
		throw new EnterpriseSecurityException("Method Not implemented");	
	}
	
	
}
?>