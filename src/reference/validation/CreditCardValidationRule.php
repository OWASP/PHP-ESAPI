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

class CreditCardValidationRule extends BaseValidationRule {
	private $ccrule=null;
	public function CreditCardValidationRule ( $typeName, $encoder, $format=null) {
		parent::BaseValidationRule($typeName, $encoder);
		$this->ccrule=$this->getCCRule($encoder);
	}
	private function getCCRule($encoder) {
		global $ESAPI;
		$config = ESAPI::getSecurityConfiguration();
		$pattern=$config->getValidationPattern("CreditCard");
		$ccr = new StringValidationRule ( "ccrule", $this->encoder,$pattern );
		$ccr->setMaximumLength(19);
		$ccr->setAllowNull(false);
		return $ccr;
	}
	
	public function getValid($context,$input,$errorlist=null ) {
		if ( strlen($input)==0 ) {
			if ( allowNull ) return null;
			throw new ValidationException($context.": Input credit card required","Input credit card required: context=".$context.", input="+$input,$context);
		}
		$canonical=$this->ccrule->getValid($context,$input);
		$canonical=preg_replace('/[^0-9]/','',$canonical);
		$odd=!strlen($canonical)%2;
		$sum=0;
		for($i=strlen($canonical)-1;$i>=0;$i--) {
			$n=$canonical[$i];
			$odd=!$odd;
			if ( $odd) {
				$sum+=$n;
			} else {
				$x=$n*2;
				$sum+=$x>9?$x-9:$x;
			}			
		}
		if ( ($sum%10)!=0 ) {
			throw new ValidationException($context.": Invalid credit card input","Invalid credit card input: context="+$context,$context);
		}
		return $canonical;
	}
	public function sanitize ( $context, $input) {
		// TODO: this code is broken. just a reminder to get it working.
		global $ESAPI;
		return $this->whitelist($input, $ESAPI->DefaultEncoder->CHAR_DIGITS);
	}
}
?>