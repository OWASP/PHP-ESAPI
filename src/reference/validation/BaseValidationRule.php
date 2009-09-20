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

require_once dirname ( __FILE__ ) . '/../../ValidationRule.php';

abstract class BaseValidationRule implements ValidationRule {
	private $typeName = null;
	protected $allowNull = false;
	protected $encoder = null;
	
	protected function BaseValidationRule($typeName, $encoder) {
		if ($encoder) {
			$this->encoder = $encoder;
		} else {
		    // TODO: need to figure out how to do this for PHP
			// $this->encoder = ESAPI::encoder;
		}
		$this->typeName = $typeName;
	}
	
	public function setAllowNull($flag) {
		if ($flag) {
			$this->allowNull = true;
		} else {
			$this->allowNull = false;
		}
	}
	
	public function getAllowNull() {
		return $this->allowNull;
	}
	
	public function getTypeName() {
		return $this->typeName;
	}
	
	public function setTypeName($typeName) {
		$this->typeName ( $typeName );
	}
	
	public function setEncoder($encoder) {
		$this->encoder = $encoder;
	}
	
	public function assertValid($context, $input) {
		getValid ( $context, $input );
	}
	
	public function getValid($context, $input, $errorList = null) {
		try {
			return getValid ( $context, $input );
		} catch ( ValidationException $e ) {
			$errorList->addError ( $context, $e );
		}
		return null;
	}
	public function getSafe($context, $input) {
		try {
			return $this->getValid ( $context, $input );
		} catch ( ValidationException $e ) {
			return $this->sanitize ( $context, $input );
		}
	}
	
	public function isValid($context, $input) {
		try {
			getValid ( $context, $input );
			return true;
		} catch ( ValidationException $e ) {
			return false;
		} catch ( Exception $e ) {
			return false;
		}
	}
	// TODO: the "list" is not used in the java version. I assume it is suppose to provide a 
	//       list of valid characters. Need to read up on specs to see how this is supposed to 
	//       work. Right now, the java version appears to allow only digits which is what this
	//	     function attempts to provide.
	

	public function whitelist($input, $list = null) {
		return preg_replace ( "/[^0-9]/", '', $input );
	}

}
?>