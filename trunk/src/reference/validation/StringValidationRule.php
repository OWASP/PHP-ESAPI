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
	protected $blacklistPatterns;
	protected $minLength=0;
	protected $maxLength=PHP_INT_MAX;
	
	public function StringValidationRule ($typeName, $encoder=NULL, $whiteListPattern=NULL ) {
		parent::BaseValidationRule($typeName, $encoder);
		$this->whitelistPatterns=array();
		$this->blacklistPatterns=array();
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
	public function addBlacklistPattern ( $pattern ) {
		try {
		    array_push($this->blacklistPatterns,$pattern);
		} catch (Exception $e) {
			throw new RuntimeException("Validation misconfiguration, problem with specified pattern: ".$pattern,$e);
		}
	}
	public function setMinimumLength ( $length ) {
		$this->minLength=$length;
	}
	public function setMaximumLength ($length) {
		$this->maxLength=$length;
	}
	public function getValid($context, $input, $errorList = null) {
		if ( strlen($input)==0 ) {
			if ( $this->allowNull ) return null;
			throw new ValidationException ( $context.": Input required.", "Input required: context=".$context+" ");
		}
		$length=strlen($input);
		if ( $length<$this->minLength) throw new ValidationException ( $context.": Invalid input. The minimum Length of ".$this->minLength." was not reached","Input did not reach minimum allows length of ".$this->minLength);
		if ( $length>$this->maxLength) throw new ValidationException ( $context.": Invalid input. The maximum length of ".$this->maxLength." was exceeded","Input did exceed maximum lenght of ".$this->maxLength);
		// check whitelist
		foreach ( $this->whitelistPatterns as $pattern ) {
			if ( ! preg_match("/$pattern/",$input) ){
				throw new ValidationException ( $context.": Invalid input. Please conform to the regex ".$pattern,$context);
			}
		}
		// check blacklist
		foreach ( $this->blacklistPatterns as $pattern ) {
			if ( preg_match("/%pattern/",$input)) {
				throw new ValidationException ( $context.": Invalid input. Dangerous input matching ".$pattern, $context);
			}
		}
		return $input;
	}
	public function sanitize ( $context, $input ) {
		return $this->whitelist($input,$DefaultEncoder->CHAR_ALPHANUMERICS);	
	}
}

?>