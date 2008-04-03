<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 * 
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * http://www.owasp.org/esapi.
 *
 * Copyright (c) 2007 - The OWASP Foundation
 * 
 * The ESAPI is published by OWASP under the LGPL. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 * 
 * @author Jeff Williams <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @package org.owasp.esapi
 * @since 2007
 */

require_once("errors/org.owasp.esapi.AccessControlException.php");
require_once("interfaces/org.owasp.esapi.IRandomizer.php");

/**
 * Reference implementation of the IAccessReferenceMap interface. This
 * implementation generates random 6 character alphanumeric strings for indirect
 * references. It is possible to use simple integers as indirect references, but
 * the random string approach provides a certain level of protection from CSRF
 * attacks, because an attacker would have difficulty guessing the indirect
 * reference.
 * 
 * @author Jeff Williams (jeff.williams@aspectsecurity.com)
 * @since June 1, 2007
 * @see org.owasp.esapi.interfaces.IAccessReferenceMap
 */
class AccessReferenceMap implements IAccessReferenceMap {

	/** The itod. */
	private $itod;

	/** The dtoi. */
	private $dtoi;

	/** The random. */
	private $random;

	/**
	 * This AccessReferenceMap implementation uses short random strings to
	 * create a layer of indirection. Other possible implementations would use
	 * simple integers as indirect references.
	 */
	public function AccessReferenceMap() {
		// call update to set up the references
		$itod = array();

		/** The dtoi. */
		$dtoi = array();

		/** The random. */
		$this->random = ESAPI::randomizer();
	}

	/**
	 * Instantiates a new access reference map.
	 * 
	 * @param directReferences
	 *            the direct references
	 */
	public function AccessReferenceMap($directReferences) {
		update($directReferences);
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see org.owasp.esapi.interfaces.IAccessReferenceMap#iterator()
	 */
	public function iterator() {
		$sorted = new ArrayObject(asort($this->dtoi));
		return $sorted->getIterator();
	}
	
	/**
	 * Adds a direct reference and a new random indirect reference, overwriting any existing values.
	 * @param direct
	 */
	public function addDirectReference($direct) {
		$indirect = $this->random->getRandomString(6, Encoder::CHAR_ALPHANUMERICS);
		$this->itod['indirect'] = $direct;
		$this->dtoi['direct'] = $indirect;
	}
	
	// FIXME: add addDirectRef and removeDirectRef to IAccessReferenceMap
	// FIXME: add test code for add/remove direct ref
	
	/**
	 * Remove a direct reference and the corresponding indirect reference.
	 * @param direct
	 */
	public function removeDirectReference($direct) {
		if ( isset($this->dtoi[$direct] ) ) {
			unset($this->itod[$this->dtoi[$direct]]);
			unset($this->dtoi[$direct]);
		}
	}

	/*
	 * This preserves any existing mappings for items that are still in the new
	 * list. You could regenerate new indirect references every time, but that
	 * might mess up anything that previously used an indirect reference, such
	 * as a URL parameter.
	 */
	/**
	 * Update.
	 * 
	 * @param directReferences
	 *            the direct references
	 */
	public final function update($directReferences) {
		$dtoi_old = $this->dtoi->__clone();
		$this->dtoi = array();
		$this->itod = array();

		$directReferences = ArrayObject($directReferences);
		$i = $directReferences->getIterator();
		while ($i->valid()) {
			$direct = $i->current();

			// get the old indirect reference
			$indirect = $dtoi_old[$direct];

			// if the old reference is null, then create a new one that doesn't
			// collide with any existing indirect references
			if (empty($indirect )) {
				do {
					$indirect = $this->random->getRandomString(6, Encoder.CHAR_ALPHANUMERICS);
				} while (itod.keySet().contains(indirect));
			}
			$this->itod[$indirect] = $direct;
			$this->dtoi[$direct] = $indirect;
			
			$i->next();
		}
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see org.owasp.esapi.interfaces.IAccessReferenceMap#getIndirectReference(java.lang.String)
	 */
	public function getIndirectReference($directReference) {
		return $this->dtoi['directReference'];
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see org.owasp.esapi.interfaces.IAccessReferenceMap#getDirectReference(java.lang.String)
	 */
	public function getDirectReference($indirectReference) {
		if (isset($this->itod[$indirectReference])) {
			return $this->itod[$indirectReference];
		}
		throw new AccessControlException("Access denied", "Request for invalid indirect reference: " + $indirectReference);
	}
}
?>