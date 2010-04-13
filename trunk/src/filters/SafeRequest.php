<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project.
 *
 * PHP version 5.2
 *
 * LICENSE: This source file is subject to the New BSD license.  You should read
 * and accept the LICENSE before you use, modify, and/or redistribute this
 * software.
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   SVN: $Id$
 * @link      http://www.owasp.org/index.php/ESAPI
 */


/**
 * SafeRequest requires ValidationException.
 */
require_once dirname(__FILE__) . '/../errors/ValidationException.php';

/**
 * This request wrapper simply overrides unsafe methods in the
 * HttpServletRequest API with safe versions that return canonicalized data
 * where possible. The wrapper returns a safe value when a validation error is
 * detected, including stripped or empty strings.
 *
 * PHP version 5.2
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @version   Release: @package_version@
 * @link      http://www.owasp.org/index.php/ESAPI
 */
class SafeRequest
{
    /*
     * Ascii character sets defining printable, non-alphanumeric characters
     * permitted in various HTTP request contexts.
     * ' !"#$%&\'()*+,-./:;<=>?@[\]^_`{|}~'
     * tspecials: '()<>@,;:\"/[]?={}' +SP +HT
     */
    const CHARS_HTTP_COOKIE_NAME  = '_';
    const CHARS_HTTP_COOKIE_VALUE = '!"#$%&\'()*+-./:<=>?@[\]^_`{|}~';
    const CHARS_HTTP_HEADER_NAME  = '-_';
    const CHARS_HTTP_HEADER_VALUE = ' !"#$%&\'()*+,-./;:<=>?@[\]^_`{|}~';
    const CHARS_HTTP_QUERY_STRING = ' &()*+,-./;:=?_';
    const CHARS_HTTP_HOSTNAME  = '-._';
    const CHARS_HTTP_REMOTE_USER  = '!#$%&\'*+-.^_`|~';

    const CHARS_FILESYSTEM_PATH   = ' !#$%&\'()+,-./=@[\]^_`{}~';

    const CHARS_ALPHA   = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const CHARS_NUMERIC = '0123456789';

    const ORD_TAB = 0x09;

    const PATTERN_REQUEST_METHOD   = '^(GET|HEAD|POST|TRACE|OPTIONS)$';
    const PATTERN_REQUEST_AUTHTYPE
        = '^([dD][iI][gG][eE][sS][tT]|[bB][aA][sS][iI][cC])$';
    const PATTERN_HOST_NAME
        = '^(?:(?:[0-9a-zA-Z][0-9a-zA-Z\-]{0,61}[0-9a-zA-Z])\.)*[a-zA-Z]{2,4}$';
    const PATTERN_IPV4_ADDRESS
        = '^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$';

    private $_serverGlobals      = null;

    private $_authType           = null;  // AUTH_TYPE
    private $_contentLength      = null;  // CONTENT_LENGTH
    private $_contentType        = null;  // CONTENT_TYPE
    private $_headers            = null;  // HTTP_*
    private $_pathInfo           = null;  // PATH_INFO
    private $_pathTranslated     = null;  // PATH_TRANSLATED
    private $_queryString        = null;  // QUERY_STRING
    private $_remoteAddr         = null;  // REMOTE_ADDR
    private $_remoteHost         = null;  // REMOTE_HOST
    private $_remoteUser         = null;  // REMOTE_USER
    private $_method             = null;  // REQUEST_METHOD
    private $_servletPath        = null;  // ? SCRIPT_NAME
    private $_serverName         = null;  // SERVER_NAME
    private $_serverPort         = null;  // SERVER_PORT // TODO is default value 0 a wise choice?
    private $_protocol           = null;  // SERVER_PROTOCOL

    private $_cookies            = null;
    private $_dateHeader         = null;  // TODO $_dateHeader
    private $_headerNames        = null;  // TODO $_headerNames possibly
    private $_requestedSessionId = null;  // TODO $_requestedSessionId
    private $_requestURI         = null;  // TODO $_requestURI
    private $_session            = null;  // TODO $_session
    private $_entity             = null;  // TODO $_entity php://input possibly
    private $_characterEncoding  = null;  // TODO $_characterEncoding of $_entity possibly
    private $_parameterNames     = null;  // TODO $_parameterNames for logrequest
    private $_parameterValues    = null;  // TODO $_parameterValues for logrequest
    private $_parameterMap       = null;  // TODO $_parameterMap for logrequest

    private $_validator = null;
    private $_encoder   = null;
    private $_auditor   = null;

    /*
     *   CGI NOT USED:
     *   GATEWAY_INTERFACE
     *   REMOTE_IDENT
     *   SERVER_SOFTWARE
     */


    /**
     * SafeRequest can be forced to use the supplied cookies, headers and server
     * globals by passing an array containing the following keys: 'cookies',
     * 'headers', 'env'.  The values for each of the keys should be an associative
     * array e.g. 'headers' => array('REQUEST_METHOD' => 'GET').
     * If any of the three options keys are not supplied then those elements will be
     * extracted from the actual request.
     * TODO accept a string like: 'GET / HTTP/1.1\r\nHost:example.com\r\n\r\n'
     *
     * @param null|array $options array (optional) of HTTP Request elements.
     */
    public function __construct($options = null)
    {
        include_once dirname(__FILE__) . '/../reference/DefaultEncoder.php';
        include_once dirname(__FILE__) . '/../codecs/HTMLEntityCodec.php';
        include_once dirname(__FILE__) . '/../codecs/PercentCodec.php';
        $codecs = array(
            new HTMLEntityCodec,
            new PercentCodec
        );
        $this->_encoder    = new DefaultEncoder($codecs);
        $this->_auditor    = ESAPI::getAuditor('SafeRequest');
        $this->_validator  = ESAPI::getValidator();

        if ($options !== null && is_array($options)) {
            if (array_key_exists('cookies', $options)) {
                $this->_cookies = $this->_validateCookies($options['cookies']);
            }
            if (array_key_exists('headers', $options)) {
                $this->_headers = $this->_validateHeaders($options['headers']);
            }
            if (array_key_exists('env', $options)) {
                $this->_serverGlobals
                    = $this->_canonicalizeServerGlobals($options['env']);
            }
        }
    }


    /**
     * Sets the encoder instance to be used for encoding/decoding, canonicalization
     * and validation.
     *
     * @param Encoder $encoder An instance of the Encoder interface.
     *
     * @return null
     */
    public function setEncoder($encoder)
    {
        if ($encoder instanceof Encoder == false) {
            throw new InvalidArgumentException(
                'setEncoder expects an object of class Encoder!'
            );
        }
        $this->_encoder = $encoder;
    }


    /**
     * Returns the value of $_SERVER['AUTH_TYPE'] if it is present or an
     * empty string if it is not.
     *
     * @return string Authentication Scheme.
     */
    public function getAuthType()
    {
        if ($this->_authType !== null) {
            return $this->_authType;
        }

        $key = 'AUTH_TYPE';
        $pattern = self::PATTERN_REQUEST_AUTHTYPE;
        $canon   = $this->getServerGlobal($key);
        $authType  = null;
        try {
            $authType = $this->_getIfValid(
                'HTTP Request Auth Scheme validation',
                $canon, $pattern, $key, 6, true
            );
        } catch (Exception $e) {
            // NoOp - already logged.
        }

        if ($authType === null) {
            $authType = '';
        }

        $this->_authType = $authType;
        return $this->_authType;
    }


    /**
     * Returns the value of $_SERVER['CONTENT_LENGTH'] if it is present or zero
     * otherwise.
     *
     * @return int Length of the Request Entity.
     */
    public function getContentLength()
    {
        if ($this->_contentLength !== null) {
            return $this->_contentLength;
        }

        $key   = 'CONTENT_LENGTH';
        $canon = $this->getServerGlobal($key);
        $isValid = $this->_validator->isValidInteger(
            'HTTP Request Content-Length validation',
            $canon, 0, PHP_INT_MAX, true
        );
        $len = null;
        if ($isValid == true) {
            $len = (int) $canon;
        } else {
            $len = 0;
        }

        $this->_contentLength = $len;
        return $this->_contentLength;
    }


    /**
     * Returns the value of $_SERVER['CONTENT_TYPE'] if it is present or an
     * empty string if it is not.
     *
     * @return string Content type of the Request Entity.
     */
    public function getContentType()
    {
        if ($this->_contentType !== null) {
            return $this->_contentType;
        }

        $key          = 'CONTENT_TYPE';
        $c            = array(self::CHARS_HTTP_HEADER_VALUE);
        $charset      = $this->_hexifyCharsForPattern($c);
        $pattern      = "^[a-zA-Z0-9{$charset}]+$";

        $canon        = $this->getServerGlobal($key);
        $contentType  = null;
        try {
            $contentType = $this->_getIfValid(
                'HTTP Request Content Type validation',
                $canon, $pattern, $key, 4096, true
            );
        } catch (Exception $e) {
            // NoOp - already logged.
        }

        if ($contentType === null) {
            $contentType = '';
        }

        $this->_contentType = $contentType;
        return $this->_contentType;
    }


    /**
     * Returns the value of $_SERVER['PATH_INFO'] if it is present or an
     * empty string if it is not.
     *
     * @return string Path Info.
     */
    public function getPathInfo()
    {
        if ($this->_pathInfo !== null) {
            return $this->_pathInfo;
        }

        $key      = 'PATH_INFO';
        $c        = array(self::CHARS_HTTP_HEADER_VALUE);
        $charset  = $this->_hexifyCharsForPattern($c);
        $pattern  = "^[a-zA-Z0-9{$charset}]+$";

        $canon    = $this->getServerGlobal($key);
        $pathInfo = null;
        try {
            $pathInfo = $this->_getIfValid(
                'HTTP Request Path Info validation',
                $canon, $pattern, $key, 4096, true
            );
        } catch (Exception $e) {
            // NoOp - already logged.
        }

        if ($pathInfo === null) {
            $pathInfo = '';
        }

        $this->_pathInfo = $pathInfo;
        return $this->_pathInfo;
    }


    /**
     * Returns the value of $_SERVER['PATH_TRANSLATED'] if it is present or an
     * empty string if it is not.
     *
     * @return string OS filesystem equivalent of Path Info.
     */
    public function getPathTranslated()
    {
        if ($this->_pathTranslated !== null) {
            return $this->_pathTranslated;
        }

        $key      = 'PATH_TRANSLATED';
        $c        = array(self::CHARS_FILESYSTEM_PATH);
        $charset  = $this->_hexifyCharsForPattern($c);
        $pattern  = "^[a-zA-Z0-9{$charset}]+$";

        $canon          = $this->getServerGlobal($key);
        $pathTranslated = null;
        try {
            $pathTranslated = $this->_getIfValid(
                'HTTP Request Path Translated validation',
                $canon, $pattern, $key, 4096, true
            );
        } catch (Exception $e) {
            // NoOp - already logged.
        }

        if ($pathTranslated === null) {
            $pathTranslated = '';
        }

        $this->_pathTranslated = $pathTranslated;
        return $this->_pathTranslated;
    }


    /**
     * Returns the value of $_SERVER['QUERY_STRING'] if it is present or an
     * empty string if it is not.
     *
     * @return string Query String.
     */
    public function getQueryString()
    {
        if ($this->_queryString !== null) {
            return $this->_queryString;
        }

        $key      = 'QUERY_STRING';
        $c        = array(self::CHARS_HTTP_QUERY_STRING);
        $charset  = $this->_hexifyCharsForPattern($c);
        $pattern  = "^[a-zA-Z0-9{$charset}]+$";

        $canon       = $this->getServerGlobal($key);
        $queryString = null;
        try {
            $queryString = $this->_getIfValid(
                'HTTP Request Query String validation',
                $canon, $pattern, $key, 4096, true
            );
        } catch (Exception $e) {
            // NoOp - already logged.
        }

        if ($queryString === null) {
            $queryString = '';
        }

        $this->_queryString = $queryString;
        return $this->_queryString;
    }


    /**
     * Returns the value of $_SERVER['REMOTE_ADDR'] if it is present or an
     * empty string if it is not.
     *
     * @return string Requesting Agent IP Address.
     */
    public function getRemoteAddr()
    {
        if ($this->_remoteAddr !== null) {
            return $this->_remoteAddr;
        }

        $key      = 'REMOTE_ADDR';
        $pattern  = self::PATTERN_IPV4_ADDRESS;

        $canon      = $this->getServerGlobal($key);
        $remoteAddr = null;
        try {
            $remoteAddr = $this->_getIfValid(
                'HTTP Request Remote Address validation',
                $canon, $pattern, $key, 15, true
            );
        } catch (Exception $e) {
            // NoOp - already logged.
        }

        if ($remoteAddr === null) {
            $remoteAddr = '';
        }

        $this->_remoteAddr = $remoteAddr;
        return $this->_remoteAddr;
    }


    /**
     * Returns the value of $_SERVER['REMOTE_HOST'] if it is present or an
     * empty string if it is not.
     *
     * @return string Requesting Agent FQDN.
     */
    public function getRemoteHost()
    {
        if ($this->_remoteHost !== null) {
            return $this->_remoteHost;
        }

        $key      = 'REMOTE_HOST';
        $pattern  = self::PATTERN_HOST_NAME;

        $canon      = $this->getServerGlobal($key);
        $remoteHost = null;
        try {
            $remoteHost = $this->_getIfValid(
                'HTTP Request Remote Host FQDN validation',
                $canon, $pattern, $key, 255, true
            );
        } catch (Exception $e) {
            // NoOp - already logged.
        }

        if ($remoteHost === null) {
            $remoteHost = '';
        }

        $this->_remoteHost = $remoteHost;
        return $this->_remoteHost;
    }


    /**
     * Returns the value of $_SERVER['REMOTE_USER'] if it is present or an
     * empty string if it is not.
     *
     * @return string Remote User ID for Basic Authentication.
     */
    public function getRemoteUser()
    {
        if ($this->_remoteUser !== null) {
            return $this->_remoteUser;
        }

        $key      = 'REMOTE_USER';
        $c        = array(self::CHARS_HTTP_REMOTE_USER);
        $charset  = $this->_hexifyCharsForPattern($c);
        $pattern  = "^[a-zA-Z0-9{$charset}]+$";

        $canon      = $this->getServerGlobal($key);
        $remoteUser = null;
        try {
            $remoteUser = $this->_getIfValid(
                'HTTP Request Remote User validation',
                $canon, $pattern, $key, 255, true
            );
        } catch (Exception $e) {
            // NoOp - already logged.
        }

        if ($remoteUser === null) {
            $remoteUser = '';
        }

        $this->_remoteUser = $remoteUser;
        return $this->_remoteUser;
    }


    /**
     * Returns the value of $_SERVER['REQUEST_METHOD'] if it is present or an
     * empty string if it is not.
     *
     * @return string Request Method.
     */
    public function getMethod()
    {
        if ($this->_method !== null) {
            return $this->_method;
        }

        $key     = 'REQUEST_METHOD';
        $pattern = self::PATTERN_REQUEST_METHOD;

        $canon  = $this->getServerGlobal($key);
        $method = null;
        try {
            $method = $this->_getIfValid(
                'HTTP Request Method Validation',
                $canon, $pattern, $key, 7, false
            );
        } catch (Exception $e) {
            // NoOp - already logged.
        }
        if ($method === null) {
            $method = '';
        }

        $this->_method = $method;
        return $this->_method;
    }


    /**
     * Returns the value of $_SERVER['SERVER_NAME'] if it is present or an
     * empty string if it is not.
     *
     * @return string Server Name or IP Address.
     */
    public function getServerName()
    {
        if ($this->_serverName !== null) {
            return $this->_serverName;
        }

        $key      = 'SERVER_NAME';
        $pattern  = self::PATTERN_IPV4_ADDRESS;

        $canon      = $this->getServerGlobal($key);
        $serverName = null;
        try {
            $serverName = $this->_getIfValid(
                'HTTP Request Server Address validation',
                $canon, $pattern, $key, 15, true
            );
        } catch (Exception $e) {
            // NoOp - already logged.
        }

        if ($serverName === null) {
            $pattern = self::PATTERN_HOST_NAME;
            try {
                $serverName = $this->_getIfValid(
                    'HTTP Request Server Name validation',
                    $canon, $pattern, $key, 255, true
                );
            } catch (Exception $e) {
                // NoOp - already logged.
            }
        }

        if ($serverName === null) {
            $serverName = '';
        }

        $this->_serverName = $serverName;
        return $this->_serverName;
    }


    /**
     * Returns the value of $_SERVER['SERVER_PORT'] if it is present or zero if it
     * is not.
     *
     * @return int Server Port Number.
     */
    public function getServerPort()
    {
        if ($this->_serverPort !== null) {
            return $this->_serverPort;
        }

        $key        = 'SERVER_PORT';
        $canon      = $this->getServerGlobal($key);
        $serverPort = null;
        $isValid = $this->_validator->isValidInteger(
            'HTTP Request Server Port validation',
            $canon, 0, 65535, true
        );
        if ($isValid == true) {
            $serverPort = (int) $canon;
        } else {
            $serverPort = 0;
        }

        $this->_serverPort = $serverPort;
        return $this->_serverPort;
    }


    /**
     * Returns an associative array of HTTP Headers.
     *
     * @return array HTTP Headers.
     */
    public function getHeaders()
    {
        if ($this->_headers !== null) {
            return $this->_headers;
        }

        if ($this->_serverGlobals === null) {
            $this->getServerGlobals();
        }

        $this->_headers = $this->_validateHeaders($this->_serverGlobals);
        return $this->_headers;
    }


    private function _validateHeaders($ary)
    {
        $charset = array(self::CHARS_HTTP_HEADER_NAME);
        $keyCharset = $this->_hexifyCharsForPattern($charset);
        $ptnKey = "^[a-zA-Z0-9{$keyCharset}]+$";

        $charset = array(self::CHARS_HTTP_HEADER_VALUE, self::ORD_TAB);
        $valCharset = $this->_hexifyCharsForPattern($charset);
        $ptnVal = "^[a-zA-Z0-9{$valCharset}]+$";

        $tmp = array();
        foreach ($ary as $unvalidatedKey => $unvalidatedVal) {
            try
            {
                $safeKey = $this->_getIfValid(
                    '$_SERVER Index', $unvalidatedKey, $ptnKey,
                    'HTTP Header Validator', PHP_INT_MAX, false
                );
                if (mb_substr($safeKey, 0, 5, 'ASCII') == 'HTTP_') {
                    $safeVal = $this->_getIfValid(
                        '$_SERVER HTTP_* Value', $unvalidatedVal, $ptnVal,
                        'HTTP Header Validator', PHP_INT_MAX, false
                    );
                    $tmp[$safeKey] = $safeVal;
                }
            }
            catch (Exception $e)
            {
                // NoOp
            }
        }
        return $tmp;
    }


    /**
     * Retreives a named HTTP header value.
     *
     * @param string $key Name of the http header value to retreive.
     *
     * @return null|string valid, canonicalised header value or null if it is not
     *                     present in the header or was present, but invalid.
     */
    public function getHeader($key)
    {
        if (! is_string($key) || $key == '') {
            return null;
        }
        if ($this->_headers === null) {
            $this->getHeaders();
        }

        if (! array_key_exists($key, $this->_headers)) {
            return null;
        }

        return $this->_headers[$key];
    }


    /**
     * Returns an associative array of HTTP Cookies.
     *
     * @return array HTTP Cookies.
     */
    public function getCookies()
    {
        if ($this->_cookies !== null) {
            return $this->_cookies;
        }

        $this->_cookies = $this->_validateCookies($_COOKIES);

        return $this->_cookies;

    }


    private function _validateCookies($ary)
    {
        if ($this->_cookies !== null) {
            return $this->_cookies;
        }

        $charset = array(self::CHARS_HTTP_COOKIE_NAME);
        $keyCharset = $this->_hexifyCharsForPattern($charset);
        $ptnKey = "^[a-zA-Z0-9{$keyCharset}]+$";

        $charset = array(self::CHARS_HTTP_COOKIE_VALUE);
        $valCharset = $this->_hexifyCharsForPattern($charset);
        $ptnVal = "^[a-zA-Z0-9{$valCharset}]+$";

        $tmp = array();
        foreach ($ary as $unvalidatedKey => $unvalidatedVal) {
            try {
                $safeKey = $this->_getIfValid(
                    '$_COOKIES Index', $unvalidatedKey, $ptnKey,
                    'HTTP Cookie Name Validator', 4094, false
                );
                $maxValLen = 4096 - 1 - mb_strlen($safeKey, 'ASCII');
                $safeVal = $this->_getIfValid(
                    '$_COOKIES Index', $unvalidatedVal, $ptnVal,
                    'HTTP Cookie Value Validator', $maxValLen, false
                );
                $tmp[$safeKey] = $safeVal;
            } catch (Exception $e) {
                // Validation or Intrusion Exceptions perform auto logging.
            }
        }
        return $tmp;

    }


    /**
     * Retreives a named http cookie value.
     *
     * @param string $name Name of the cookie value to retreive.
     *
     * @return null|string valid, canonicalised cookie value or null if it is not
     *                     present in the header or was present, but invalid.
     */
    public function getCookie($name)
    {
        if (! is_string($name) || $name == '') {
            return null;
        }
        if ($this->_cookies === null) {
            $this->getCookies();
        }

        if (! array_key_exists($name, $this->_cookies)) {
            return null;
        }

        return $this->_cookies[$name];
    }


    /**
     * A convenience method to retrieve an array of PHP Server Globals.  Both the
     * keys and values are canonicalized and those that generate exceptions are
     * not added to the array.
     *
     * @return array Zero or more Canonicalized PHP Server Globals.
     */
    private function _getServerGlobals()
    {
        if ($this->_serverGlobals !== null) {
            return $this->_serverGlobals;
        }

        $this->_serverGlobals = $this->_canonicalizeServerGlobals($_SERVER);

        return $this->_serverGlobals;
    }


    private function _canonicalizeServerGlobals($ary)
    {
        $tmp = array();
        foreach ($ary as $unsafeKey => $unsafeVal) {
            if (! is_string($unsafeVal)) {
                continue;
            }
            try {
                $canonKey = $this->_encoder->canonicalize($unsafeKey);
                $canonVal = $this->_encoder->canonicalize($unsafeVal);
                $tmp[$canonKey] = $canonVal;
            } catch (Exception $e) {
                // Validation or Intrusion Exceptions perform auto logging.
            }
        }
        return $tmp;
    }


    /**
     * Returns the value of the PHP Server Global with the supplied name. If the
     * variable does not exist then null is returned.
     *
     * @param string $key Index name for a value in the $_SERVER array.
     *
     * @return string|null Value of a $_SERVER variable or null.
     */
    public function getServerGlobal($key)
    {
        if (! is_string($key) || $key == '') {
            return null;
        }
        if ($this->_serverGlobals === null) {
            $this->_getServerGlobals();
        }

        if (array_key_exists($key, $this->_serverGlobals)) {
            return $this->_serverGlobals[$key];
        }
        $key = strtoupper($key);
        if (array_key_exists($key, $this->_serverGlobals)) {
            return $this->_serverGlobals[$key];
        }

        return null;
    }


    public function getRequestedSessionId()
    {
        throw new EnterpriseSecurityException(
            'getRequestedSessionId method Not implemented.',
            'getRequestedSessionId method Not implemented.'
        );
    }


    public function getSession()
    {
        throw new EnterpriseSecurityException(
            'getSession method Not implemented.',
            'getSession method Not implemented.'
        );
    }


    public function getSessionCreate()
    {
        throw new EnterpriseSecurityException(
            'getSessionCreate method Not implemented.',
            'getSessionCreate method Not implemented.'
        );
    }


    public function isRequestedSessionIdFromCookie()
    {
        throw new EnterpriseSecurityException(
            'isRequestedSessionIdFromCookie method Not implemented.',
            'isRequestedSessionIdFromCookie method Not implemented.'
        );
    }


    public function isRequestedSessionIdFromURL()
    {
        throw new EnterpriseSecurityException(
            'isRequestedSessionIdFromURL method Not implemented.',
            'isRequestedSessionIdFromURL method Not implemented.'
        );
    }


    public function isRequestedSessionIdValid()
    {
        throw new EnterpriseSecurityException(
            'isRequestedSessionIdValid method Not implemented.',
            'isRequestedSessionIdValid method Not implemented.'
        );
    }


    public function getCharacterEncoding()
    {
        throw new EnterpriseSecurityException(
            'getCharacterEncoding method Not implemented.',
            'getCharacterEncoding method Not implemented.'
        );
    }


    public function getParameter()
    {
        throw new EnterpriseSecurityException(
            'getParameter method Not implemented.',
            'getParameter method Not implemented.'
        );
    }


    public function getParameterNames()
    {
        throw new EnterpriseSecurityException(
            'getParameterNames method Not implemented.',
            'getParameterNames method Not implemented.'
        );
    }


    public function getParameterValues()
    {
        throw new EnterpriseSecurityException(
            'getParameterValues method Not implemented.',
            'getParameterValues method Not implemented.'
        );
    }


    public function getParameterMap()
    {
        throw new EnterpriseSecurityException(
            'getParameterMap method Not implemented.',
            'getParameterMap method Not implemented.'
        );
    }


    public function getHeaderNames()
    {
        throw new EnterpriseSecurityException(
            'getHeaderNames method Not implemented.',
            'getHeaderNames method Not implemented.'
        );
    }


    public function getDateHeader()
    {
        throw new EnterpriseSecurityException(
            'getDateHeader method Not implemented.',
            'getDateHeader method Not implemented.'
        );
    }


    private function _getIfValid($context, $input, $pattern, $type, $maxLength, $allowNull)
    {
        $validationRule = new StringValidationRule($type, $this->_encoder);

        if ($pattern != null) {
            $validationRule->addWhitelistPattern($pattern);
        }

        $validationRule->setMaximumLength($maxLength);
        $validationRule->setAllowNull($allowNull);

        return $validationRule->getValid($context, $input);
    }


    private function _hexifyCharsForPattern($charsets)
    {
        $s = '';
        foreach ($charsets as $set) {
            if ($set === (int) $set) {
                $s .= chr($set);
            } else {
                $s .= $set;
            }
        }

        $hex = '';
        $limit = mb_strlen($s, 'ASCII');
        for ($i = 0; $i < $limit; $i++) {
            list(, $ord) = unpack("C", mb_substr($s, $i, 1, 'ASCII'));
            $h = dechex($ord);
            $pad = mb_strlen($h, 'ASCII') == 1 ? '0' : '';
            $hex .= '\\x' . $pad . $h;
        }
        return $hex;
    }


}
