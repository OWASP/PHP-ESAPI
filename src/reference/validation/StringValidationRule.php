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

require_once "BaseValidationRule.php";

class StringValidationRule extends BaseValidationRule {
	protected $whitelistPatterns;
	protected $minLength=0;
	protected $maxLength=PHP_INT_MAX;
	
	public function StringValidationRule ($typeName, $encoder=NULL, $whiteListPattern=NULL ) {
		parent::BaseValidationRule($typeName, $encoder);
		$this->whitelistPatterns=array();
		if ( $whiteListPattern ) {
			$this->addWhitelistPattern( $whiteListPattern );
		}
	}
	public function addWhitelistPattern ($pattern ){
		try {
			array_push($this->whitelistPatterns,$pattern);
		} catch ( Exception $e ) {
			throw new RuntimeExcpetion ("Validation misconfiguration , problem with specified pattern: ".$pattern,$e);
		}
	}
	public function setMinimumLength ( $length ) {
		$this->minLength=$length;
	}
	public function setMaximumLength ($length) {
		$this->maxLength=$length;
	}
	
	public function sanitize ( $context, $input ) {
		return $this->whitelist($input, $this->whitelistPatterns);
	}
}

?>