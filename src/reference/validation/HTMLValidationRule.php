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

// TODO : configuration of htmlpurifier
//
// $config->set('Core.Encoding', 'UTF-8'); // replace with your encoding
// $config->set('HTML.Doctype', 'XHTML 1.0 Transitional'); // replace with your doctype
//
//


require_once "BaseValidationRule.php";
require_once "../lib/htmlpurifier/HTMLPurifier.includes.php";
class HTMLValidationRule extends StringValidationRule {
	// private static $antiSamyPolicy=null;
	private static $logger=null;
	private static $purifier=null;	
	public function HTMLValidationRule($typeName, $encoder=null,$whitelistPattern=null) {
		global $ESAPI;
		parent::BaseValidationRule($typeName, $encoder,$whitelistPattern);
		// TODO: logging
		// $this->$logger=$ESAPI->getLogger("HTMLValidationRule");
		$this->purifier = new HTMLPurifier();
		if ( ! $this->purifier ) {
		   throw new ValidationException("Could not initialize HTMLPurifier","HTMLPurifier failure",$e);
		}
	}
	public function getValid($context, $input,$errorlist=null) {
		$clean_html = $this->purifier->purify( $input );
		return $clean_html;
	}
	public function sanitize($context,$input) {
		$safe='';
		try {
			$safe=$this->purifier->purify($input);
		} catch ( VaidationException $e ) {
			// just retrun safe
		}
		return $safe;
	}
	
}
?>