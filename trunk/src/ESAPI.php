<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * <a href="http://www.owasp.org/index.php/ESAPI">http://www.owasp.org/index.php/ESAPI</a>.
 *
 * Copyright (c) 2008 - The OWASP Foundation
 *
 * The ESAPI is published by OWASP under the BSD license. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 *
 * @author PHP Port by Andrew van der Stock <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @author See J2EE Authors
 * @created 2008
 * @package org.owasp.esapi
 */


require_once "reference/DefaultEncoder.php";
require_once "reference/DefaultExecutor.php";
require_once "reference/DefaultHTTPUtilities.php";
require_once "reference/DefaultIntrusionDetector.php";
require_once "reference/DefaultRandomizer.php";
require_once "reference/DefaultSecurityConfiguration.php";
require_once "reference/DefaultValidator.php";
require_once "reference/FileBasedAccessController.php";
require_once "reference/FileBasedAuthenticator.php";
require_once "reference/JavaEncryptor.php";
require_once "reference/JavaLogFactory.php";

/**
 * ESAPI locator class to make it easy to get a concrete implementation of the
 * various ESAPI classes. Use the setters to override the reference implementations
 * with instances of any custom ESAPI implementations.
 */

class ESAPI {

	private static $accessController = null;

	private static $authenticator = null;

	private static $encoder = null;

	private static $encryptor = null;

	private static $executor = null;

	private static $httpUtilities = null;

	private static $intrusionDetector = null;

	private static $logFactory = null;

	private static $defaultLogger = null;

	private static $randomizer = null;

	private static $securityConfiguration = null;

	private static $validator = null;

	/**
	 * prevent instantiation of this class
	 */
	private function ESAPI() {
	}

	public static function currentRequest() {
		return httpUtilities().getCurrentRequest();
	}

	public static function currentResponse() {
		return httpUtilities().getCurrentResponse();
	}

	/**
	 * @return the accessController
	 */
	public static function accessController() {
		if (ESAPI::accessController == null)
			ESAPI::accessController = new FileBasedAccessController();
		return ESAPI::accessController;
	}

	/**
	 * @param accessController
	 *            the accessController to set
	 */
	public static function setAccessController($accessController) {
		ESAPI::accessController = $accessController;
	}

	/**
	 * @return the authenticator
	 */
	public static function authenticator() {
		if (ESAPI::authenticator == null)
			ESAPI::authenticator = new FileBasedAuthenticator();
		return ESAPI::authenticator;
	}

	/**
	 * @param authenticator
	 *            the authenticator to set
	 */
	public static function setAuthenticator($authenticator) {
		ESAPI::authenticator = $authenticator;
	}

	/**
	 * @return the encoder
	 */
	public static function encoder() {
		if (ESAPI::encoder == null)
			ESAPI::encoder = new DefaultEncoder();
		return ESAPI::encoder;
	}

	/**
	 * @param encoder
	 *            the encoder to set
	 */
	public static function setEncoder($encoder) {
		ESAPI::encoder = $encoder;
	}

	/**
	 * @return the encryptor
	 */
	public static function encryptor() {
		if (ESAPI::encryptor == null)
			ESAPI::encryptor = new JavaEncryptor();
		return ESAPI::encryptor;
	}

	/**
	 * @param encryptor
	 *            the encryptor to set
	 */
	public static function setEncryptor($encryptor) {
		ESAPI::encryptor = $encryptor;
	}

	/**
	 * @return the executor
	 */
	public static function executor() {
		if (ESAPI::executor == null)
			ESAPI::executor = new DefaultExecutor();
		return ESAPI::executor;
	}

	/**
	 * @param executor
	 *            the executor to set
	 */
	public static function setExecutor($executor) {
		ESAPI::executor = $executor;
	}

	/**
	 * @return the httpUtilities
	 */
	public static function httpUtilities() {
		if (ESAPI::httpUtilities == null)
			ESAPI::httpUtilities = new DefaultHTTPUtilities();
		return ESAPI::httpUtilities;
	}

	/**
	 * @param httpUtilities
	 *            the httpUtilities to set
	 */
	public static function setHttpUtilities($httpUtilities) {
		ESAPI::httpUtilities = $httpUtilities;
	}

	/**
	 * @return the intrusionDetector
	 */
	public static function intrusionDetector() {
		if (ESAPI::intrusionDetector == null)
			ESAPI::intrusionDetector = new DefaultIntrusionDetector();
		return ESAPI::intrusionDetector;
	}

	/**
	 * @param intrusionDetector
	 *            the intrusionDetector to set
	 */
	public static function setIntrusionDetector($intrusionDetector) {
		ESAPI::intrusionDetector = $intrusionDetector;
	}

	private static function logFactory() {
//		if (logFactory == null)
//			logFactory = new JavaLogFactory(securityConfiguration().getApplicationName());
//		return logFactory;
	}

	/**
	 *
	 */
//	public static function getLogger($clazz) {
//		return logFactory().getLogger(clazz);
//	}

	/**
	 *
	 */
	public static function getLogger($name) {
		return logFactory().getLogger($name);
	}

	public static function log() {
		if ($self->defaultLogger == null)
			$self->defaultLogger = logFactory().getLogger("");
		return $self->defaultLogger;
	}

	 /**
	 * @param factory the log factory to set
	 */
	 public static function setLogFactory($factory) {
		 ESAPI::logFactory = $factory;
	 }

	/**
	 * @return the randomizer
	 */
	public static function randomizer() {
		if (ESAPI::randomizer == null)
			ESAPI::randomizer = new DefaultRandomizer();
		return ESAPI::randomizer;
	}

	/**
	 * @param randomizer
	 *            the randomizer to set
	 */
	public static function setRandomizer($randomizer) {
		ESAPI::randomizer = $randomizer;
	}

	/**
	 * @return the securityConfiguration
	 */
	public static function securityConfiguration() {
		if (ESAPI::securityConfiguration == null)
			ESAPI::securityConfiguration = new DefaultSecurityConfiguration();
		return ESAPI::securityConfiguration;
	}

	/**
	 * @param securityConfiguration
	 *            the securityConfiguration to set
	 */
	public static function setSecurityConfiguration($securityConfiguration) {
		ESAPI::securityConfiguration = $securityConfiguration;
	}

	/**
	 * @return the validator
	 */
	public static function validator() {
		if (ESAPI::validator == null)
			ESAPI::validator = new DefaultValidator();
		return ESAPI::validator;
	}

	/**
	 * @param validator
	 *            the validator to set
	 */
	public static function setValidator($validator) {
		ESAPI::validator = $validator;
	}

}

?>