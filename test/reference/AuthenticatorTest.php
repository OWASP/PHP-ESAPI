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
 * @author Bipin Upadhyay <http://projectbee.org/blog/contact/>
 * @created 2009
 */

class AuthenticatorTest extends UnitTestCase {

        private $CHAR_ALPHANUMERICS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890';

        function setUp() {
                global $ESAPI;

                if ( !isset($ESAPI)) {
                        $ESAPI = new ESAPI(dirname(__FILE__).'/../testresources/ESAPI.xml');
                }
        }

        function tearDown() {

        }

        function testCreatUserWithAnExistingAccountName() {
                $accountName = ESAPI::getRandomizer()->getRandomString(8, $this->CHAR_ALPHANUMERICS);
                $authenticator = ESAPI::getAuthenticator();
                $password = $authenticator->generateStrongPassword();
                $user = $authenticator->createUser($accountName, $password, $password);
                $this->assertTrue($user->verifyPassword($password));

                try {
                        $authenticator->createUser($accountName, $password, $password);
                        $this->fail();
                }catch (AuthenticationException $e ) {
                        $this->pass();
                }
        }

        public function testCreatUserWithNonMatchingPasswords() {
                $authenticator = ESAPI::getAuthenticator();
                try {
                        $authenticator->createUser(ESAPI::getRandomizer()->getRandomString(8, $this->CHAR_ALPHANUMERICS), "password1", "password2");
                        $this->fail();
                } catch (AuthenticationException $e) {
                        $this->pass();
                }
        }

        public function testCreatUserWithWeakPassword() {
                $authenticator = ESAPI::getAuthenticator();
                try {
                        $authenticator->createUser(ESAPI::getRandomizer()->getRandomString(8, $this->CHAR_ALPHANUMERICS), "weak", "weak");
                        $this->fail();
                } catch (AuthenticationException $e) {
                        $this->pass();
                }
        }

        public function testCreatUserWithNullUserName() {
                $authenticator = ESAPI::getAuthenticator();
                try {
                        $authenticator->createUser(null, "sdb5572g^&^", "sdb5572g^&^");
                        $this->fail();
                } catch (AuthenticationException $e) {
                        $this->pass();
                }
        }

        public function testCreatUserWithNullPassword() {
                $authenticator = ESAPI::getAuthenticator();
                try {
                        $authenticator->createUser(ESAPI::getRandomizer()->getRandomString(8, $this->CHAR_ALPHANUMERICS), null, null);
                        $this->fail();
                } catch (AuthenticationException $e) {
                        $this->pass();
                }
        }

        public function testCreatUserWithStrongPassword() {
                $authenticator = ESAPI::getAuthenticator();
                $accountName = ESAPI::getRandomizer()->getRandomString(8, $this->CHAR_ALPHANUMERICS);
                $password = ESAPI::getRandomizer()->getRandomString(15, $this->CHAR_ALPHANUMERICS);
                try {
                        $authenticator->createUser($accountName, $password, $password);
                        $this->pass();
                } catch (AuthenticationException $e) {
                        $this->fail();
                }
        }
}
?>