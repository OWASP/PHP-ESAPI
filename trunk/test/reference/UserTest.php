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
 * @author AbiusX (@ jFramework.info)
 * @version 1.04
 * @created 2009
 */
require_once dirname(__FILE__) . '/../../src/ESAPI.php';
require_once dirname(__FILE__) . '/../../src/reference/DefaultUser.php';
class UserTest extends UnitTestCase
{
    function setUp ()
    {}
    function tearDown ()
    {}
    private $usernames = array("testaccount8","lqc3cmws" , "yjwvnp7q" , "testaccount1" , "dbctro2g" , "isfirstrequest" , "vr0vr7m8" , "omfp2ssc" , "test5" , "hfmcrx2o" , "test8" , "9hravwpm" , "testuser1" , "testaccount7" , "satlhgga" , "test9" , "yytpw3k0" , "uiwuzzon" , "qka44qdh");
    /**
     * returns a random user from those who exist in the users.txt
     * @return DefaultUsert
     */
    function getUser ($index = null)
    {
        static $in = 0;
        if ($index === null)
            $index = $in ++;
        //if ($index >= count($this->usernames))
        //    return null;
        //else
            return new DefaultUser($this->usernames[rand(0,count($this->usernames)-1)]);
    }
    function randomString ($Length = 8)
    {
        $alpha = 'abcdefghijklmnopqrstuvwxyz';
        $str = '';
        for ($i = 0; $i < $Length; ++ $i)
            $str .= $alpha[rand(0, 25)];
        return $str;
    }
    ###############################################
    #XXX: done
    /**
     * Test of testAddRole method, of class org.owasp.esapi.$user->
     * 
     * @exception Exception
     * 				any Exception thrown by testing addRole()
     */
    public function testAddRole () #throws Exception {
    {
        $user = $this->getUser();
        $role = $this->randomString();
        $user->addRole($role);
        $this->assertTrue($user->isInRole($role));
        $this->assertFalse($user->isInRole("ridiculous"));
    }
	/**
	 * Test of addRoles method, of class org.owasp.esapi.$user->
	 * 
	 * @#throws AuthenticationException
	 *             the authentication exception
	 */
	public function testAddRoles() #throws AuthenticationException {
	{
        $user = $this->getUser();
        $Roles=array();
        $Roles[]="rolea";
		$Roles[]="roleb";
		$user->addRoles($Roles);
		$this->assertTrue($user->isInRole("rolea"));
		$this->assertTrue($user->isInRole("roleb"));
		$this->assertFalse($user->isInRole("ridiculous"));
	}

	/**
	 * Test of changePassword method, of class org.owasp.esapi.$user->
	 * 
	 * @#throws Exception
	 *             the exception
	 */
	public function testChangePassword() #throws Exception {
	{
        #TODO: this relies on Authenticator
		$oldPassword = "Password12!@";
        $user=$this->getUser();
		$password1 = "SomethingElse34#$";
		try {
		    $user->changePassword($oldPassword, $password1, $password1);
		}
		catch (AuthenticationException $e)
		{
		    $this->fail();
		    return;
		}
		$this->assertTrue($user->verifyPassword($password1));
		$password2 = "YetAnother56%^";
		$user->changePassword($password1, $password2, $password2);
		try {
			$user->changePassword($password2, $password1, $password1);
			$this->fail("Shouldn't be able to reuse a password");
		} catch( AuthenticationException $e ) {
			// expected
		}
		$this->assertTrue($user->verifyPassword($password2));
		$this->assertFalse($user->verifyPassword("badpass"));
	}

	/**
	 * Test of disable method, of class org.owasp.esapi.$user->
	 * 
	 * @#throws AuthenticationException
	 *             the authentication exception
	 */
	public function testDisable() #throws AuthenticationException {
	{
        $user = $this->getUser();
	    $user->enable();
		$this->assertTrue($user->isEnabled());
		$user->disable();
		$this->assertFalse($user->isEnabled());
	}

	/**
	 * Test of enable method, of class org.owasp.esapi.$user->
	 * 
	 * @#throws AuthenticationException
	 *             the authentication exception
	 */
	public function testEnable() #throws AuthenticationException {
	{
        $user = $this->getUser();
	    $user->enable();
		$this->assertTrue($user->isEnabled());
		$user->disable();
		$this->assertFalse($user->isEnabled());
	}

	/**
	 * Test of failedLoginCount lockout, of class org.owasp.esapi.$user->
	 * 
	 * @#throws AuthenticationException
	 *             the authentication exception
	 * @#throws EncryptionException
	 *             any EncryptionExceptions thrown by testing failedLoginLockout()
	 */
	public function testFailedLoginLockout() #throws AuthenticationException, EncryptionException {
	{
        $user=$this->getUser();
		$user->enable();
        
		$user->setFailedLoginCount(0);
		#TODO: change the number of fails if allowed login tries changes from 3
		$user->unlock();
		try {
    		$user->loginWithPassword("ridiculous");
		} catch( AuthenticationException $e ) { 
    		// expected
    	}
		$this->assertFalse($user->isLocked());

		try {
    		$user->loginWithPassword("ridiculous");
		} catch( AuthenticationException $e ) { 
    		// expected
    	}
		$this->assertFalse($user->isLocked());

		try {
    		$user->loginWithPassword("ridiculous");
		} catch( AuthenticationException $e ) { 
    		// expected
    	}
		$this->assertTrue($user->isLocked());
	}

	/**
	 * Test of getAccountName method, of class org.owasp.esapi.$user->
	 * 
	 * @#throws AuthenticationException
	 *             the authentication exception
	 */
	public function testGetAccountName() #throws AuthenticationException {
	{
        $user=$this->getUser();
        $accountName=$this->randomString();
        $user->setAccountName($accountName);
		$this->assertTrue($accountName== $user->getAccountName());
		$this->assertFalse("ridiculous"==$user->getAccountName());
	}

	/**
	 * Test get last failed login time.
	 * 
	 * @#throws Exception
	 *             the exception
	 */
	public function testGetLastFailedLoginTime() #throws Exception {
	{
        $user=$this->getUser();
	    try {
    		$user->loginWithPassword("ridiculous");
		} catch( AuthenticationException $e ) { 
    		// expected
    	}
		$time1= $user->getLastFailedLoginTime();
		usleep(100*1000); // need a short delay to separate attempts
		try {
    		$user->loginWithPassword("ridiculous");
		} catch( AuthenticationException $e ) { 
    		// expected
    	}
		$time2= $user->getLastFailedLoginTime();
		$this->assertTrue($time1<=$time2);
	}

	/**
	 * Test get last login time.
	 * 
	 * @#throws Exception
	 *             the exception
	 */
	public function testGetLastLoginTime() #throws Exception {
	{
        $user=$this->getUser();
	    $user->enable();
	    $user->unlock();
        $user->setLastLoginTime(time());
		$t1= $user->getLastLoginTime();
		usleep(10*1000); // need a short delay to separate attempts
        $user->setLastLoginTime(time());
		$t2= $user->getLastLoginTime();
		$this->assertTrue($t2>=$t1);
	}

	/**
	 * Test getLastPasswordChangeTime method, of class org.owasp.esapi.$user->
	 * 
	 * @#throws Exception
	 *             the exception
	 */
	public function testGetLastPasswordChangeTime() #throws Exception {
	{
        #TODO: this relies on changePassword and that relies on authenticator
	    $user=$this->getUser();
	    $t1= $user->getLastPasswordChangeTime();
		usleep(1000*1000); // need a short delay to separate attempts
        $newPassword=$this->randomString();
		//String newPassword = ESAPI.authenticator().generateStrongPassword(user, "getLastPasswordChangeTime");
		try {
        $user->changePassword("getLastPasswordChangeTime", $newPassword, $newPassword);
		}
		catch (AuthenticationException $e)
		{
		    $this->fail();
		    return;
		}
		$t2 = $user->getLastPasswordChangeTime();
		$this->assertTrue($t2>$t1);
	}

	/**
	 * Test of getRoles method, of class org.owasp.esapi.$user->
     *
     * @#throws Exception
     */
	public function testGetRoles() #throws Exception {
	{
        $user=$this->getUser();
        $role=$this->randomString();
	    $user->addRole($role);
		$roles = $user->getRoles();
		$this->assertTrue(count($roles) > 0);
		$this->assertTrue(in_array($role,$roles));
	}

	/**
	 * Test of getScreenName method, of class org.owasp.esapi.$user->
	 * 
	 * @#throws AuthenticationException
	 *             the authentication exception
	 */
	public function testGetScreenName() #throws AuthenticationException {
	{
	    #TODO: we have problems with screenName here!
        $user=$this->getUser();
        $screenName=$this->randomString(7);
		$user->setScreenName($screenName);
		$this->assertTrue($screenName==$user->getScreenName());
		$this->assertFalse("ridiculous"==($user->getScreenName()));
	}

    /**
     *
     * @#throws org.owasp.esapi.errors.AuthenticationException
     */
    public function testGetSessions() #throws AuthenticationException {
    {
        $user=$this->getUser();
        $user->addSession( "session1");
        $user->addSession( "session2" );
        $user->addSession( "session3" );
        $sessions = $user->getSessions();
        $this->assertTrue(count($sessions) == 3);
	}
	
	
    /**
     *
     */
    public function testAddSession() {
        $user=$this->getUser();
        $user->addSession("session1");
        $sessions=$user->getSessions();
        $this->assertTrue(array_key_exists("session1",$sessions));
    }
	
    /**
     *
     */
    public function testRemoveSession() {
        $user=$this->getUser();
        $user->addSession("session1");
        $user->addSession("session2");
        $user->addSession("session3");
        $user->removeSession("session2");
        $sessions=$user->getSessions();
        $this->assertFalse(array_key_exists("session2",$sessions));
    }
	
	/**
	 * Test of incrementFailedLoginCount method, of class org.owasp.esapi.$user->
	 * 
	 * @#throws AuthenticationException
	 *             the authentication exception
	 */
	public function testIncrementFailedLoginCount() #throws AuthenticationException {
	{
	    $user=$this->getUser();
	    $user->enable();
	    $user->unlock();
	    $user->setFailedLoginCount(0);
		$this->assertTrue($user->getFailedLoginCount()==0);
		try {
			$user->loginWithPassword("ridiculous");
		} catch (AuthenticationException $e) {
			// expected
		}
		$this->assertTrue($user->getFailedLoginCount()==1);
		try {
			$user->loginWithPassword("ridiculous");
		} catch (AuthenticationException $e) {
			// expected
		}
		$this->assertTrue(2==$user->getFailedLoginCount());
		try {
			$user->loginWithPassword("ridiculous");
		} catch (AuthenticationException $e) {
			// expected
		}
		$this->assertTrue(3==$user->getFailedLoginCount());
		try {
			$user->loginWithPassword("ridiculous");
		} catch (AuthenticationException $e) {
			// expected
		}
		$this->assertTrue($user->isLocked());
	}

	/**
	 * Test of isEnabled method, of class org.owasp.esapi.$user->
	 * 
	 * @#throws AuthenticationException
	 *             the authentication exception
	 */
	public function testIsEnabled() #throws AuthenticationException {
	{
        $user=$this->getUser();
	    $user->disable();
		$this->assertFalse($user->isEnabled());
		$user->enable();
		$this->assertTrue($user->isEnabled());
	}

    
    
	/**
	 * Test of isInRole method, of class org.owasp.esapi.$user->
	 * 
	 * @#throws AuthenticationException
	 *             the authentication exception
	 */
	public function testIsInRole() #throws AuthenticationException {
	{
	    $user=$this->getUser();
		$role = "TestRole";
		$this->assertFalse($user->isInRole($role));
		$user->addRole($role);
		$this->assertTrue($user->isInRole($role));
		$this->assertFalse($user->isInRole("Ridiculous"));
	}

	/**
	 * Test of isLocked method, of class org.owasp.esapi.$user->
	 * 
	 * @#throws AuthenticationException
	 *             the authentication exception
	 */
	public function testIsLocked() #throws AuthenticationException {
	{
        $user=$this->getUser();
	    $user->lock();
		$this->assertTrue($user->isLocked());
		$user->unlock();
		$this->assertFalse($user->isLocked());
	}

	/**
	 * Test of isSessionAbsoluteTimeout method, of class
	 * org.owasp.esapi.IntrusionDetector.
	 * 
	 * @#throws AuthenticationException
	 *             the authentication exception
	 */
	public function testIsSessionAbsoluteTimeout() #throws AuthenticationException {
	{
		$user=$this->getUser();
		// set session creation -3 hours (default is 2 hour timeout)		
		$user->addSession("session1");
        $_SESSION[$user->getAccountId()]["session1"]['start']=time()-(3600*2+1);
        $this->assertTrue($user->isSessionAbsoluteTimeout("session1"));
		
		// set session creation -1 hour (default is 2 hour timeout)
        $_SESSION[$user->getAccountId()]["session1"]['start']=time()-(3600*2-1);
        $this->assertFalse($user->isSessionAbsoluteTimeout("session1"));
	}

	/**
	 * Test of isSessionTimeout method, of class
	 * org.owasp.esapi.IntrusionDetector.
	 * 
	 * @#throws AuthenticationException
	 *             the authentication exception
	 */
	public function testIsSessionTimeout() #throws AuthenticationException {
	{
		$user=$this->getUser();
		// set session creation -3 hours (default is 2 hour timeout)		
		$user->addSession("session1");
        $_SESSION[$user->getAccountId()]["session1"]['lastUpdate']=time()-(3600+1);
        $this->assertTrue($user->isSessionTimeout("session1"));
		
		// set session creation -1 hour (default is 2 hour timeout)
        $_SESSION[$user->getAccountId()]["session1"]['lastUpdate']=time()-(3600-1);
        $this->assertFalse($user->isSessionTimeout("session1"));
	}

	/**
	 * Test of lockAccount method, of class org.owasp.esapi.$user->
	 * 
	 * @#throws AuthenticationException
	 *             the authentication exception
	 */
	public function testLock() #throws AuthenticationException {
	{
        $user=$this->getUser();
        $user->lock();
		$this->assertTrue($user->isLocked());
		$user->unlock();
		$this->assertFalse($user->isLocked());
	}

	/**
	 * Test of loginWithPassword method, of class org.owasp.esapi.$user->
	 * 
	 * @#throws AuthenticationException
	 *             the authentication exception
	 */
	public function testLoginWithPassword() #throws AuthenticationException {
	{
	    #TODO: its not working yet
	    $user=$this->getUser();
	    $user->enable();
	    $user->unlock();
		try {
	    $user->loginWithPassword("real password here");
		}
		catch(AuthenticationException $e)
		{
		    $this->Fail();
		    return ;
		}
		$this->assertTrue($user->isLoggedIn());
		$user->logout();
		$this->assertFalse($user->isLoggedIn());
		$this->assertFalse($user->isLocked());
		try {
			$user->loginWithPassword("ridiculous");
		} catch (AuthenticationException $e) {
			// expected
		}
		$this->assertFalse($user->isLoggedIn());
		try {
			$user->loginWithPassword("ridiculous");
		} catch (AuthenticationException $e) {
			// expected
		}
		try {
			$user->loginWithPassword("ridiculous");
		} catch (AuthenticationException $e) {
			// expected
		}
		$this->assertTrue($user->isLocked());
		$user->unlock();
		$this->assertTrue($user->getFailedLoginCount() == 0 );
	}


	/**
	 * Test of logout method, of class org.owasp.esapi.$user->
	 * 
	 * @#throws AuthenticationException
	 *             the authentication exception
	 */
	public function testLogout() #throws AuthenticationException {
	{
	    #TODO: this is not working
        $user=$this->getUser();
	    $user->enable();
	    $user->unlock();
	    try {
		    $user->loginWithPassword("real password here");
	    }
	    catch (AuthenticationException $e)
	    {
	        #expected
	    }
		$this->assertTrue($user->isLoggedIn());
		#also put the checks about session here
		// get new session after user logs in
		$user->logout();
		$this->assertFalse($user->isLoggedIn());
	}

	/**
	 * Test of testRemoveRole method, of class org.owasp.esapi.$user->
	 * 
	 * @#throws AuthenticationException
	 *             the authentication exception
	 */
	public function testRemoveRole() #throws AuthenticationException {
	{
        $user=$this->getUser();
        $role=$this->randomString();
		$user->addRole($role);
		$this->assertTrue($user->isInRole($role));
		$user->removeRole($role);
		$this->assertFalse($user->isInRole($role));
	}

	/**
	 * Test of testResetCSRFToken method, of class org.owasp.esapi.$user->
	 * 
	 * @#throws AuthenticationException
	 *             the authentication exception
	 */
	public function testResetCSRFToken() #throws AuthenticationException {
	{
        #TODO: not working    
		$user=$this->getUser();
        $token1= $user->resetCSRFToken();
        $token2 = $user->resetCSRFToken();
        $this->assertFalse( $token1==$token2 );
	}
	
	/**
	 * Test of setAccountName method, of class org.owasp.esapi.$user->
     *
     * @#throws AuthenticationException
     */
	public function testSetAccountName() #throws AuthenticationException {
	{
        $user=$this->getUser();
	    $accountName=$this->randomString(7);
		$user->setAccountName($accountName);
		$this->assertTrue($accountName==$user->getAccountName());
		$this->assertFalse("ridiculous"==$user->getAccountName());
	}

	/**
	 * Test of setExpirationTime method, of class org.owasp.esapi.$user->
     *
     * @#throws Exception
     */
	public function testSetExpirationTime() #throws Exception {
	{
        $user=$this->getUser();
	    $user->setExpirationTime(0);
		$this->assertTrue( $user->isExpired() );
	}

	
	/**
	 * Test of setRoles method, of class org.owasp.esapi.$user->
	 * 
	 * @#throws AuthenticationException
	 *             the authentication exception
	 */
	public function testSetRoles() #throws AuthenticationException {
	{
        $user=$this->getUser();
	    $user->addRole("user");
		$this->assertTrue($user->isInRole("user"));
        $roles=array();
		$roles[]=("rolea");
		$roles[]=("roleb");
		$user->setRoles($roles);
		$this->assertFalse($user->isInRole("user"));
		$this->assertTrue($user->isInRole("rolea"));
		$this->assertTrue($user->isInRole("roleb"));
		$this->assertFalse($user->isInRole("ridiculous"));
	}

	/**
	 * Test of setScreenName method, of class org.owasp.esapi.$user->
	 * 
	 * @#throws AuthenticationException
	 *             the authentication exception
	 */
	public function testSetScreenName() #throws AuthenticationException {
	{
        $user=$this->getUser();
        $screenName=$this->randomString();
        $user->setScreenName($screenName);
		$this->assertTrue($screenName==$user->getScreenName());
		$this->assertFalse("ridiculous"==$user->getScreenName());
	}

	/**
	 * Test of unlockAccount method, of class org.owasp.esapi.$user->
	 * 
	 * @#throws AuthenticationException
	 *             the authentication exception
	 */
	public function testUnlock() #throws AuthenticationException {
	{
        $user=$this->getUser();
	    $user->lock();
		$this->assertTrue($user->isLocked());
		$user->unlock();
		$this->assertFalse($user->isLocked());
	}
}
?>