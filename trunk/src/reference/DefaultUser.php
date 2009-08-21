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
 * @author AbiusX
 * @created 2009
 * @since 1.4
 * @version 1.03
 */

/**
 * @author AbiusX
 *  A FEW NOTICES:
 * Old Password hashed are not used here anywhere?!

 * This is how this class works:
 * In the constructor, You provide it with a username.
 * this class then loads that user's data from users.txt in
 * ../../test/testresources/users.txt
 * into an associative array, namely $UserInfo
 * whenever you get or set a user parameter, its changed in this array
 * the destructor saves the array to the users.txt file.
 * 
 *  Sessions:
 *  I store every user's session info on
 *  $_SESSION[userid]
 *  
 *  for every session assigned to a user, there exists
 *  $_SESSION[userid][session_id()]
 *  
 *  which is an array containing 
 *  	start => timestamp of session assignment (for absolute timeout)
 *  	lastUpdate => timestamp of last session update
 *  
 *   I've used time() instead of dates, and timestamps everywhere
 *   
 *   FIXME: in the java implementation, there are three more fields for a user
 *   = lastHostAddress
 *   = accountID
 *   = screenName
 *   which are not in users.txt
 *   i've declared them as public members at the beginning of class code.
 */

require_once dirname(__FILE__).'/../User.php';

define ("MAX_ROLE_LENGTH",250);


class DefaultUser implements User {
	
    public $lastHostAddress;
    public $loggedIn=false;
    public $screenName="";
        
    //Configs
    public $allowedLoginAttempts=5;
    public $sessionTimeout=3600; #seconds
    public $sessionAbsoluteTimeout=60000; 
    //TODO: load these from config
    
    private $Username = null;
	private $Password = null;
	private $UID=null;
	
	private $UserInfo=null;
	private $_PathToUsersFiles="../../test/testresources/users.txt";
	/**
	 * This is intended to compute the password hash for a password
	 * @param String $Password
	 * @return String the hash
	 */
    function hashPassword($Password)
    {
        //TODO: code this
    }
	/**
	 * This array holds the keys for users fields in order and is used in parseUserInfo()
	 * @var Array
	 */
	private $UserInfoFields=array(
	"accountName" ,  
	"hashedPassword" ,  
	"roles" ,  
	"locked" ,  
	"enabled" ,  
	"rememberToken" ,  
	"csrfToken" ,  
	"oldPasswordHashes" ,  
	"lastPasswordChangeTime" ,  
	"lastLoginTime" ,  
	"lastFailedLoginTime" ,  
	"expirationTime" ,  
	 "failedLoginCount"
	);
	private function setUserInfo($Field,$Value)
	{
	     $this->UserInfo[$Field]=$Value;   
	}
	private function getUserInfo($Field)
	{
	    return $this->UserInfo[$Field];
	}
	private function parseUserInfo($Data)
	{
	    $Data=explode(" | ",$Data);
	    $n=0;
	    $this->UserInfo=array();
	    foreach($Data as $D)
	    {
	        $this->UserInfo[$this->UserInfoFields[$n++]]=$D;
	    }
	}
	private function readUserInfo()
	{
	    $Compare=$this->Username;
	    $fp=fopen(dirname(__FILE__).$this->_PathToUsersFiles,"r");
	    if (!$fp) throw new Exception("Can not open the users.txt file!");
	    while (!feof($fp))
	    {
            $Line=fgets($fp);
            if (substr($Line,0,strlen($Compare))==$Compare)
            {
                $Data=$Line;
                $this->parseUserInfo($Data);
                break;
            }
	    }
	    fclose($fp);
	}
	private function writeUserInfo()
	{
	    $Compare=$this->Username;
	    $fp=fopen(dirname(__FILE__).$this->_PathToUsersFiles,"r");
	    if (!$fp) throw new Exception("Can not open the users.txt file!");
        $Data="";
	    while (!feof($fp))
	    {
            $Line=fgets($fp);
            if (substr($Line,0,strlen($Compare))!=$Compare)
                $Data.=$Line;
	    }
	    fclose($fp);
	    $fp=fopen(dirname(__FILE__).$this->_PathToUsersFiles,"w");
	    fwrite($fp,$Data);
	    fwrite($fp,implode(" | ",$this->UserInfo)."\n");
	    fclose($fp);
	}
	function __construct($Username)//, $Password1, $Password2) 
	{
		$this->Username = $Username;
		//$this->Password = $Password1;
		$this->UID=$Username; //FIXME: this should be the userID not username
		
		$this->readUserInfo();
		
	}
	
	function __destruct()
	{
	    $this->writeUserInfo();
	}
	/**
     * Gets this user's account name.
     * 
     * @return the account name
     */
    function getAccountName()
    {
    	return $this->Username;
    }
    /**
     * Adds a role to this user's account.
     * 
     * @param role 
     * 		the role to add
     * 
     * @throws AuthenticationException 
     * 		the authentication exception
     */
    function addRole($Role) //throws AuthenticationException
    {
        $Roles=$this->getUserInfo("roles");
        $Roles=explode(",",$Roles);
        $RolesF=array_flip($Roles);
        if ($ESAPI->validator->isValidInput("addRole", $Role, "RoleName", MAX_ROLE_LENGTH, false))
        {
            if (array_key_exists($Role,$RolesF))
            {
                return false;
            }
            else
            {
                $Roles[]=$Role;
                $this->setUserInfo("roles",implode(",",$Roles));
    			//TODO: $Logger.info(Logger.SECURITY_SUCCESS, "Role " + $Role + " added to " + $this->getAccountName() );
                
            }
        }
        else
            throw new AuthenticationException( "Add role failed", "Attempt to add invalid role ". $Role ." to " . $this->getAccountName() );
        
    }

    /**
     * Adds a set of roles to this user's account.
     * 
     * @param Array $newRoles 
     * 		the new roles to add
     * 
     * @throws AuthenticationException 
     * 		the authentication exception
     */
    function addRoles($newRoles) //throws AuthenticationException;
    {
        foreach ($newRoles as $Role)
            $this->addRole($Role);
    }

    /**
     * Sets the user's password, performing a verification of the user's old password, the equality of the two new
     * passwords, and the strength of the new password.
     * 
     * @param oldPassword 
     * 		the old password
     * @param newPassword1 
     * 		the new password
     * @param newPassword2 
     * 		the new password - used to verify that the new password was typed correctly
     * 
     * @throws AuthenticationException 
     * 		if newPassword1 does not match newPassword2, if oldPassword does not match the stored old password, or if the new password does not meet complexity requirements 
     * @throws EncryptionException 
     */
    function changePassword($oldPassword, $newPassword1, $newPassword2) //throws AuthenticationException, EncryptionException;
    {
        if ($newPassword1!==$newPassword2)
            throw new AuthenticationException("Retype does not match Password.","Password Change Retype Mismatch");
        $realOldPass=$this->getUserInfo("hashedPassword");
        if ($realOldPass!=$this->hashPassword($oldPassword))
            throw AuthenticationException("Old Password provided is not correct.","Password Change Old Password Wrong");
        
        #TODO: add this function
        if (!CheckComplexity($newPassword1))
            throw new AuthenticationException("Password is not complex enough!","Password Change Complexity Failure");
        
        $this->setUserInfo("hashedPassword",$this->hashPassword($newPassword1));
        
        //TODO: this is the code in java version:
        #		ESAPI.authenticator().changePassword(this, oldPassword, newPassword1, newPassword2);
        
    }
    /**
     * Disable this user's account.
     */
    function disable()
    {
        $this->setUserInfo("enabled","disabled");
        #TODO: $logger->info( Logger.SECURITY_SUCCESS, "Account disabled: " + getAccountName() );
        
    }

    /**
     * Enable this user's account.
     */
    function enable()
    {
        $this->setUserInfo("enabled","disabled");
        #TODO: 		logger.info( Logger.SECURITY_SUCCESS, "Account enabled: " + getAccountName() );
        
    }

    /**
     * Gets this user's account id number.
     * 
     * @return Integer the account id
     */
    function getAccountId()
    {
        return $this->UID;
    }
    

    /**
     * Gets the CSRF token for this user's current sessions.
     * 
     * @return String the CSRF token
     */
    function getCSRFToken()
    {
        return $this->getUserInfo("csrfToken");
    }

    /**
     * Returns the date that this user's account will expire.
     *
     * @return Date representing the account expiration time.
     */
    function getExpirationTime()
    {
        return $this->getUserInfo("expirationTime");
    }

    /**
     * Returns the number of failed login attempts since the last successful login for an account. This method is
     * intended to be used as a part of the account lockout feature, to help protect against brute force attacks.
     * However, the implementor should be aware that lockouts can be used to prevent access to an application by a
     * legitimate user, and should consider the risk of denial of service.
     * 
     * @return Integer the number of failed login attempts since the last successful login
     */
    function getFailedLoginCount()
    {
        return $this->getUserInfo("failedLoginCount");
    }

    /**
     * Returns the last host address used by the user. This will be used in any log messages generated by the processing
     * of this request.
     * 
     * @return String the last host address used by the user
     */
    function getLastHostAddress()
    {
        //return (getenv("HTTP_X_FORWARDED_FOR")) ? getenv("HTTP_X_FORWARDED_FOR") : getenv("REMOTE_ADDR");
        if ($this->lastHostAddress==null)
            return "local";
        else
            return $this->lastHostAddress;
    }

	/**
     * Returns the date of the last failed login time for a user. This date should be used in a message to users after a
     * successful login, to notify them of potential attack activity on their account.
     * 
     * @return date of the last failed login
     * 
     * @throws AuthenticationException 
     * 		the authentication exception
     */
    function getLastFailedLoginTime() //throws AuthenticationException;
    {
        return $this->getUserInfo("lastFailedLoginTime");
    }

    /**
     * Returns the date of the last successful login time for a user. This date should be used in a message to users
     * after a successful login, to notify them of potential attack activity on their account.
     * 
     * @return date of the last successful login
     */
    function getLastLoginTime()
    {
        return $this->getUserInfo("lastLoginTime");
    }

    /**
     * Gets the date of user's last password change.
     * 
     * @return the date of last password change
     */
    function getLastPasswordChangeTime()
    {
       return $this->getUserInfo("lastPasswordChangeTime"); 
    }

    /**
     * Gets the roles assigned to a particular account.
     * 
     * @return Array an immutable set of roles
     */
    function getRoles()
    {
        return explode(",",$this->getUserInfo("roles"));
    }

    /**
     * Gets the screen name (alias) for the current user.
     * 
     * @return String the screen name
     */
    function getScreenName()
    {
        return $this->screenName;
        //return $this->getUserInfo("accountName");
    }

    /**
     * Adds a session for this User.
     * 
     * @param $HttpSession Just for interop
     */
    function addSession( $HttpSession=null )
    {
        if (session_id()=="") //no session established, throw some errors FIXME
         ;
        $_SESSION[$this->getAccountId()][session_id()]=array("start"=>time(),"lastUpdate"=>time());
    }
    
    /**
     * Removes a session for this User.
     * 
     * @param $HttpSession The session to remove from being associated with this user.
     */
    function removeSession( $HttpSession=null )
    {
        unset($_SESSION[$this->getAccountId()][session_id()]);
    }
    
    /**
     * Returns the list of sessions associated with this User.
     * @return Array sessions
     */
    function getSessions()
    {
        return $_SESSION[$this->getAccountId()];
    }
    
    /**
     * Increment failed login count.
     */
    function incrementFailedLoginCount()
    {
        $this->setUserInfo("failedLoginCount",$this->getUserInfo("failedLoginCount")+1);
    }
    
    function setFailedLoginCount($count) 
    {
        $this->setUserInfo("failedLoginCount",$count);
    }
    

    /**
     * Checks if user is anonymous.
     * 
     * @return true, if user is anonymous
     */
    function isAnonymous()
    {
        if ($this->UID===null) return true;
        else return false;
    }

    /**
     * Checks if this user's account is currently enabled.
     * 
     * @return true, if account is enabled 
     */
    function isEnabled()
    {
        return $this->getUserInfo("enabled")=="enabled";
    }

    /**
     * Checks if this user's account is expired.
     * 
     * @return true, if account is expired
     */
    function isExpired()
    {
        $ExpTime=$this->getUserInfo("expirationTime");
        if ($ExpTime<time()) return true;
        else return false;
    }

    /**
     * Checks if this user's account is assigned a particular role.
     * 
     * @param String $Role the role for which to check
     * 
     * @return true, if role has been assigned to user
     */
    function isInRole($Role)
    {
        $Roles=$this->getUserInfo("roles");
        $Roles=explode(",",$Roles);
        $Roles=array_flip($Roles);
        if (array_key_exists($Role,$Roles))
            return true;
        else
            return false; 
        
        
    }

    /**
     * Checks if this user's account is locked.
     * 
     * @return true, if account is locked
     */
    function isLocked()
    {
        return $this->getUserInfo("locked")=="locked";
    }

    /**
     * Tests to see if the user is currently logged in.
     * 
     * @return true, if the user is logged in
     */
    function isLoggedIn()
    {
        return !($this->UID===null);
    }

    /**
     * Tests to see if this user's session has exceeded the absolute time out based 
      * on ESAPI's configuration settings.
     * 
     * @return true, if user's session has exceeded the absolute time out
     */
    function isSessionAbsoluteTimeout()
    {
         if (isset($_SESSION[$this->getAccountId()][session_id()]['start']))
         {
             return (time()-$_SESSION[$this->getAccountId()][session_id()]['start'])>$this->sessionTimeout;
         }
         return true; //FIXME: no session data exists!
    }

    /**
      * Tests to see if the user's session has timed out from inactivity based 
      * on ESAPI's configuration settings.
      * 
      * A session may timeout prior to ESAPI's configuration setting due to 
      * the servlet container setting for session-timeout in web.xml. The 
      * following is an example of a web.xml session-timeout set for one hour. 	
      *
      * <session-config>
      *   <session-timeout>60</session-timeout> 
      * </session-config>
      * 
      * @return true, if user's session has timed out from inactivity based 
      *               on ESAPI configuration
      */
     function isSessionTimeout()
     {
         #XXX: You should add some logic to update session time somewhere!
         if (isset($_SESSION[$this->getAccountId()][session_id()]['lastUpdate']))
         {
             return (time()-$_SESSION[$this->getAccountId()][session_id()]['lastUpdate'])>$this->sessionTimeout;
         }
         return true; //FIXME: no session data exists!
     }

    /**
     * Lock this user's account.
     */
    function lock()
    {
        $this->setUserInfo("locked","locked");
    }

    /**
     * Login with password.
     * 
     * @param String $Password the password
     * @throws AuthenticationException 
     * 		if login fails
     */
    function loginWithPassword($Password) //throws AuthenticationException;
    {
        //if ($this->getUserInfo("hashedPassword")!=$this->hashPassword($Password))
            //throw new AuthenticationException("Invalid password","Invalid Password for login");

    	if ( $Password == null || $Password="" ) 
    	{
			$this->setLastFailedLoginTime(time());
			$this->incrementFailedLoginCount();
			throw new AuthenticationLoginException( "Login failed", "Missing password: " . $this->getAccountName()  );
		}
		
		// don't let disabled users log in
		if ( !$this->isEnabled() ) 
		{
			$this->setLastFailedLoginTime(time());
			$this->incrementFailedLoginCount();
			throw new AuthenticationLoginException("Login failed", "Disabled user attempt to login: " .$this->getAccountName() );
		}
		
		// don't let locked users log in
		if ( $this->isLocked() ) 
		{
			$this->setLastFailedLoginTime(time());
			$this->incrementFailedLoginCount();
			throw new AuthenticationLoginException("Login failed", "Locked user attempt to login: " .$this->getAccountName() );
		}
		
		// don't let expired users log in
		if ( $this->isExpired() ) 
		{
			$this->setLastFailedLoginTime(time());
			$this->incrementFailedLoginCount();
			throw new AuthenticationLoginException("Login failed", "Expired user attempt to login: " + accountName );
		}
		
		$this->logout();

		if ( $this->verifyPassword( $Password ) ) 
		{
			$this->loggedIn = true;
			
			/* TODO: add these
			ESAPI.httpUtilities().changeSessionIdentifier( ESAPI.currentRequest() );
			ESAPI.authenticator().setCurrentUser(this);
			*/
			$this->setLastLoginTime(time());
            //$this->setLastHostAddress( ESAPI.httpUtilities().getCurrentRequest().getRemoteHost() );
			//logger.trace(Logger.SECURITY_SUCCESS, "User logged in: " + accountName );
		} 
		else 
		{
			$this->loggedIn = false;
			$this->setLastFailedLoginTime(time());
			$this->incrementFailedLoginCount();
			if ($this->getFailedLoginCount() >= $this->allowedLoginAttempts) {
				$this->lock();
			}
			throw new AuthenticationLoginException("Login failed", "Incorrect password provided for " .$this->getAccountName() );
		}    
    
    }

    /**
     * Logout this user.
     */
    function logout()
    {
		//TODO: ESAPI.httpUtilities().killCookie( ESAPI.currentRequest(), ESAPI.currentResponse(), HTTPUtilities.REMEMBER_TOKEN_COOKIE_NAME );
		
		//HttpSession session = ESAPI.currentRequest().getSession(false);
		if (isset($_SESSION[$this->getUserId()])) 
		{
		    unset($_SESSION[$this->getUserId()]);
		}
		//TODO: ESAPI.httpUtilities().killCookie(ESAPI.currentRequest(), ESAPI.currentResponse(), "PHPSESSIONID");
		$this->loggedIn = false;
		//logger.info(Logger.SECURITY_SUCCESS, "Logout successful" );
		//ESAPI.authenticator().setCurrentUser(User.ANONYMOUS);
    }
    

    /**
     * Removes a role from this user's account.
     * 
     * @param String $Role the role to remove
     * @throws AuthenticationException 
     * 		the authentication exception
     */
    function removeRole($Role) //throws AuthenticationException;
    {
        $Roles=$this->getUserInfo("roles");
        $Roles=explode(",",$Roles);
        $Roles=array_flip($Roles);
        if (!array_key_exists($Role,$Roles))
        {
            //TODO: some error
        }
        else
        {
            unset($Roles[$Role]);
            $Roles=array_flip($Roles);
            $this->setUserInfo("roles",implode(",",$Roles));
        }
    }

    /**
     * Returns a token to be used as a prevention against CSRF attacks. This token should be added to all links and
     * forms. The application should verify that all requests contain the token, or they may have been generated by a
     * CSRF attack. It is generally best to perform the check in a centralized location, either a filter or controller.
     * See the verifyCSRFToken method.
     * 
     * @return the new CSRF token
     * 
     * @throws AuthenticationException 
     * 		the authentication exception
     */
    function resetCSRFToken() //throws AuthenticationException;
    {
        //TODO: $csrfToken = ESAPI.randomizer().getRandomString(8, DefaultEncoder.CHAR_ALPHANUMERICS);
        
        $this->setUserInfo("csrfToken",csrfToken);
		return $csrfToken;
    }

    /**
     * Sets this user's account name.
     * 
     * @param String $AccountName the new account name
     */
    function setAccountName($AccountName)
    {
        $this->setUserInfo("accountName",$AccountName);
    }

    /**
     * Sets this user's account ID
     * @param integer $AccountID
     * @return unknown_type
     */
    function setAccountID($AccountID)
    {
        $this->setUserInfo("accountID",$AccountID);
    }
    /**
     * Sets the date and time when this user's account will expire.
     * 
     * @param $ExpirationTime Timestamp the new expiration time
     */
	function setExpirationTime($ExpirationTime)
	{
	    $this->setUserInfo("expirationTime",$ExpirationTime);
	}

	/**
     * Sets the roles for this account.
     * 
     * @param Array $Roles the new roles
     * 
     * @throws AuthenticationException 
     * 		the authentication exception
     */
    function setRoles($Roles) //throws AuthenticationException;
    {
        $this->setUserInfo("roles",implode(",",$Roles));
    }

    /**
     * Sets the screen name (username alias) for this user.
     * 
     * @param String $ScreenName the new screen name
     */
    function setScreenName($ScreenName)
    {
        $this->screenName=$ScreenName;
        //$this->setUserInfo("accountName",$ScreenName);
        #FIXME: this changes account name! what is screen name?!
    }

    /**
     * Unlock this user's account.
     */
    function unlock()
    {
        $this->setUserInfo("locked","unlocked");
    }

	/**
	 * Verify that the supplied password matches the password for this user. This method
	 * is typically used for "reauthentication" for the most sensitive functions, such
	 * as transactions, changing email address, and changing other account information.
	 * 
	 * @param $Password the password that the user entered
	 * 
	 * @return true, if the password passed in matches the account's password
	 * 
	 * @throws EncryptionException 
	 */
	public function verifyPassword($Password) //throws EncryptionException;
	{
	    //TODO: 		return ESAPI.authenticator().verifyPassword(this, password);
	    
	    return ($this->getUserInfo("hashedPassword")==$this->hashPassword($Password));
	}

	/**
	 * Set the time of the last failed login for this user.
	 * 
	 * @param Integer $LastFailedLoginTime Timestamp the date and time when the user just failed to login correctly.
	 */
	function setLastFailedLoginTime($LastFailedLoginTime)
	{
	    $this->setUserInfo("lastFailedLoginTime",$LastFailedLoginTime);
	}
	
	/**
	 * Set the last remote host address used by this user.
	 * 
	 * @param $RemoteHost The address of the user's current source host.
	 */
	function setLastHostAddress($RemoteHost)
	{
		if ( $this->lastHostAddress != null && $this->lastHostAddress!=$RemoteHost)
		{
        	// returning remote address not remote hostname to prevent DNS lookup
			new AuthenticationHostException("Host change", "User session just jumped from " . $this->lastHostAddress . " to " .$RemoteHost );
		}
		$this->lastHostAddress = $RemoteHost;
    }
	
	/**
	 * Set the time of the last successful login for this user.
	 * 
	 * @param Integer $LastLoginTime Timestamp the date and time when the user just successfully logged in.
	 */
	function setLastLoginTime($LastLoginTime)
	{
	    $this->setUserInfo("lastLoginTime",$LastLoginTime);
	}
	
	/**
	 * Set the time of the last password change for this user.
	 * 
	 * @param Integer $LastPasswordChangeTime Timestamp the date and time when the user just successfully changed his/her password.
	 */
	function setLastPasswordChangeTime($LastPasswordChangeTime)
	{
	    $this->setUserInfo("lastPasswordChangeTime",$LastPasswordChangeTime);
	}

	

	/**
	 * The ANONYMOUS user is used to represent an unidentified user. Since there is
	 * always a real user, the ANONYMOUS user is better than using null to represent
	 * this.
	 */
    public final $ANONYMOUS=null;
}
?>