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
require_once dirname ( __FILE__ ) . '/../DefaultEncoder.php';

abstract class BaseValidationRule implements ValidationRule {
	private $typeName = null;
	protected $allowNull = false;
	protected $encoder = null;
	
	protected function BaseValidationRule($typeName, $encoder) {
		global $ESAPI;
		if ($encoder) {
			$this->encoder = $encoder;
		} else {
			$this->encoder = new DefaultEncoder();
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

	public function whitelist($input, $list) {
		$array = str_split($input);
		$stripped = "";
		foreach($array as $char) {
			foreach ( $list as $pattern ) {
				if ( preg_match("/$pattern/",$input) ){
					$stripped = $stripped.$char;
				}
			}		
		}
		return $stripped;		
	}

}
?>