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
require_once "../lib/htmlpurifier/HTMLPurifier.includes.php";
class HTMLValidationRule extends StringValidationRule {
	// private static $antiSamyPolicy=null;
	private static $logger=null;
	
	public function HTMLValidationRule($typeName, $encoder=null,$whitelistPattern=null) {
		global $ESAPI;
		parent::BaseValidationRule($typeName, $encoder,$whitelistPattern);
		// TODO: logging
		// $this->$logger=$ESAPI->getLogger("HTMLValidationRule");
		// TODO: antisamy replacement with HTML Purifier
		// $this->antiSamyPolicy;
		/*
		try {
			if ( antiSamyPolicy==null) {
				$in=null;
				$in=$ESAPI->securityConfiguration->getResoureStream("antisamy-esapi.xml");
				if ( $in != null ) {
					$this->antiSamyPolicy= new Policy(in);
					
				}
				if ( $this->antiSamyPolicy==null) {
					throw new IllegalArgumentException ("Can't find antisamy-esapi.xml");
				}
			}
		} catch (Exception $e ) {
			new ValidationException("Could not initialize AntiSamy","AntiSamy policy failure",$e);
		
		}
	*/	
	}
	public function getValid($context, $input,$errorlist=null) {
		// TODO: use HTML Purifier instead of AntiSamy
		// $this->invokeAntiSamy($context,$input,false);
		return $input;
	}
	public function sanitize($context,$input) {
		$safe='';
		try {
			$safe=$this->invokeAntiSamy($context,$input, false);
		} catch ( VaidationException $e ) {
			// just retrn safe
		}
		return $safe;
	}
	
}
?>