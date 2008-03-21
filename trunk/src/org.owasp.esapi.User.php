<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 * 
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project. For details, please see
 * http://www.owasp.org/ESAPI::
 *
 * Copyright (c) 2007 - The OWASP Foundation
 * 
 * The ESAPI is published by OWASP under the LGPL. You should read and accept the
 * LICENSE before you use, modify, and/or redistribute this software.
 * 
 * @author Jeff Williams <a href="http://www.aspectsecurity.com">Aspect Security</a>
 * @package org.owasp.esapi
 * @created 2007
 */
require_once("errors/org.owasp.esapi.AuthenticationAccountsException.php");
require_once("errors/org.owasp.esapi.AuthenticationCredentialsException.php");
require_once("errors/org.owasp.esapi.AuthenticationException.php");
require_once("errors/org.owasp.esapi.AuthenticationHostException.php");
require_once("errors/org.owasp.esapi.AuthenticationLoginException.php");
require_once("errors/org.owasp.esapi.EncryptionException.php");
require_once("errors/org.owasp.esapi.IntrusionException.php");
require_once("interfaces/org.owasp.esapi.ILogger.php");
require_once("interfaces/org.owasp.esapi.IUser.php");

/**
 * Reference implementation of the IUser interface. This implementation is serialized into a flat file in a simple format.
 * 
 * @author Jeff Williams (jeff.williams .at. aspectsecurity.com) <a
 *         href="http://www.aspectsecurity.com">Aspect Security</a>
 * @since June 1, 2007
 * @see org.owasp.ESAPI::interfaces.IUser
 */
class User implements IUser, Serializable {


	/** The Constant serialVersionUID. */
	private static $serialVersionUID = 1;

	/** The logger. */
	private static $logger = Logger::getLogger("ESAPI", "User");

    /** true only for the first HTTP request, false afterwards */
    private $isFirstRequest = true;
    
	/** The account name. */
	private $accountName = "";

	/** The screen name. */
	private $screenName = "";

	/** The hashed password. */
	private $hashedPassword = "";

	/** The old password hashes. */
	private $oldPasswordHashes = new ArrayList();

	/** The remember token. */
	private $rememberToken = "";

	/** The csrf token. */
	private $csrfToken = "";

	/** The roles. */
	private $roles = new HashSet();

	/** The locked. */
	private $locked = false;

	/** The logged in. */
	private $loggedIn = true;

    /** The enabled. */
	private $enabled = false;

    /** The last host address used. */
    private $lastHostAddress;

	/** The last password change time. */
	private $lastPasswordChangeTime = new Date();

	/** The last login time. */
	private $lastLoginTime = new Date();

	/** The last failed login time. */
	private $lastFailedLoginTime = new Date();
	
	/** The expiration time. */
	private $expirationTime = new Date(Long.MAX_VALUE);

	/** A flag to indicate that the password must be changed before the account can be used. */
	// FIXME: ENHANCE enable this required password change feature?
	// private boolean requiresPasswordChange = true;
	
	/** The failed login count. */
	private int failedLoginCount = 0;
    
	/** Intrusion detection events */
	private Map events = new HashMap();

    
    // FIXME: ENHANCE consider adding these for access control support
    //
    //private String authenticationMethod = null;
    //
    //private String connectionChannel = null;
    
	/**
	 * Instantiates a new user.
	 */
	protected function User() {
		// hidden
	}

	/**
	 * Instantiates a new user.
	 * 
	 * @param line
	 *            the line
	 */
	protected function User($line) {
		$parts = split("\\|", $line);
		$this->accountName = strlower(trim($parts[0]));
		// FIXME: AAA validate account name
		$this->hashedPassword = parts[1].trim();
        
		$this->roles.addAll(Arrays.asList(parts[2].trim().toLowerCase().split(" *, *")));
		$this->locked = !"unlocked".equalsIgnoreCase(parts[3].trim());
		$this->enabled = "enabled".equalsIgnoreCase(parts[4].trim());
		$this->rememberToken = parts[5].trim();

		// generate a new csrf token
        $this->resetCSRFToken();
        
		$this->oldPasswordHashes.addAll( Arrays.asList(parts[6].trim().split(" *, *")));
        $this->lastHostAddress = parts[7].trim();
        $this->lastPasswordChangeTime = new Date( Long.parseLong(parts[8].trim()));
		$this->lastLoginTime = new Date( Long.parseLong(parts[9].trim()));
		$this->lastFailedLoginTime = new Date( Long.parseLong(parts[10].trim()));
		$this->expirationTime = new Date( Long.parseLong(parts[11].trim()));
		$this->failedLoginCount = Integer.parseInt(parts[12].trim());
	}

	/**
	 * Only for use in creating the Anonymous user.
	 * 
	 * @param accountName
	 *            the account name
	 * @param password
	 *            the password
	 */
	protected function User( $accountName, $password ) {
		$this->accountName = accountName.toLowerCase();
	}

	/**
	 * Instantiates a new user.
	 * 
	 * @param accountName
	 *            the account name
	 * @param password1
	 *            the password1
	 * @param password2
	 *            the password2
	 * 
	 * @throws AuthenticationException
	 *             the authentication exception
	 */
	function User($accountName, $password1, $password2) {
		
		ESAPI::authenticator().verifyAccountNameStrength("Create User", accountName);

		if ( password1 == null ) {
			throw new AuthenticationCredentialsException( "Invalid account name", "Attempt to create account " + accountName + " with a null password" );
		}
		ESAPI::authenticator().verifyPasswordStrength(password1, null );
		
		if (!password1.equals(password2)) throw new AuthenticationCredentialsException("Passwords do not match", "Passwords for " + accountName + " do not match");
		
		$this->accountName = accountName.toLowerCase();
		try {
		    setHashedPassword( ESAPI::encryptor().hash(password1, $this->accountName) );
		} catch (EncryptionException ee) {
		    throw new AuthenticationException("Internal error", "Error hashing password for " + $this->accountName, ee);
		}
		expirationTime = new Date( System.currentTimeMillis() + (long)1000 * 60 * 60 * 24 * 90 );  // 90 days
		logger.logCritical(Logger.SECURITY, "Account created successfully: " + accountName );
	}

	/* (non-Javadoc)
	 * @see org.owasp.ESAPI::interfaces.IUser#addRole(java.lang.String)
	 */
	public function addRole($role) {
		$roleName = role.toLowerCase();
		if ( ESAPI::validator().isValidDataFromBrowser("addRole", "RoleName", roleName) ) {
			roles.add(roleName);
			logger.logCritical(Logger.SECURITY, "Role " + roleName + " added to " + getAccountName() );
		} else {
			throw new AuthenticationAccountsException( "Add role failed", "Attempt to add invalid role " + roleName + " to " + getAccountName() );
		}
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see org.owasp.ESAPI::interfaces.IUser#addRoles(java.util.Set)
	 */
	public function addRoles(Set newRoles) {
		Iterator i = newRoles.iterator();
		while(i.hasNext()) {
			addRole((String)i.next());
		}
	}

	 /**
	 * Adds a security event to the user.
	 * 
	 * @param event the event
	 */
	public function addSecurityEvent($eventName) {
		Event event = (Event)events.get( eventName );
		if ( event == null ) {
			event = new Event( eventName );
			events.put( eventName, event );
		}

		Threshold q = ESAPI::securityConfiguration().getQuota( eventName );
		if ( q.count > 0 ) {
			event.increment(q.count, q.interval);
		}
	}

	// FIXME: ENHANCE - make admin only methods separate from public API
	/**
	 * Change password.
	 * 
	 * @param newPassword1
	 *            the new password1
	 * @param newPassword2
	 *            the new password2
	 */
	protected function changePassword($newPassword1, $newPassword2) {
		setLastPasswordChangeTime(new Date());
		$newHash = ESAPI::authenticator().hashPassword(newPassword1, getAccountName() );
		setHashedPassword( newHash );
		logger.logCritical(Logger.SECURITY, "Password changed for user: " + getAccountName() );
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see org.owasp.ESAPI::interfaces.IUser#setPassword(java.lang.String, java.lang.String)
	 */
	public function changePassword($oldPassword, $newPassword1, $newPassword2) {
		if (!hashedPassword.equals(ESAPI::authenticator().hashPassword(oldPassword, getAccountName()))) {
			throw new AuthenticationCredentialsException("Password change failed", "Authentication failed for password change on user: " + getAccountName() );
		}
		if (newPassword1 == null || newPassword2 == null || !newPassword1.equals(newPassword2)) {
			throw new AuthenticationCredentialsException("Password change failed", "Passwords do not match for password change on user: " + getAccountName() );
		}
		ESAPI::authenticator().verifyPasswordStrength(newPassword1, oldPassword);
		setLastPasswordChangeTime(new Date());
		$newHash = ESAPI::authenticator().hashPassword(newPassword1, accountName);
		if (oldPasswordHashes.contains(newHash)) {
			throw new AuthenticationCredentialsException( "Password change failed", "Password change matches a recent password for user: " + getAccountName() );
		}
		setHashedPassword( newHash );
		logger.logCritical(Logger.SECURITY, "Password changed for user: " + getAccountName() );
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see org.owasp.ESAPI::interfaces.IUser#disable()
	 */
	public function disable() {
		// FIXME: ENHANCE what about disabling for a short time period - to address DOS attack?
		enabled = false;
		logger.logSpecial( "Account disabled: " + getAccountName(), null );
	}
	
	/**
	 * Dump a collection as a comma-separated list.
	 * @return the string
	 */
	protected function dump( Collection c ) {
		StringBuffer sb = new StringBuffer();
		Iterator i = c.iterator();
		while ( i.hasNext() ) {
			$s = (String)i.next();
			sb.append( s );
			if ( i.hasNext() ) sb.append( ",");
		}
		return sb.toString();
	}

	/**
	 * Enable the account
	 * 
	 * @see org.owasp.ESAPI::interfaces.IUser#enable()
	 */
	public function enable() {
		$this->enabled = true;
		logger.logSpecial( "Account enabled: " + getAccountName(), null );
	}

	/* (non-Javadoc)
	 * @see java.lang.Object#equals(java.lang.Object)
	 */
	public function equals(Object obj) {
		if (this == obj)
			return true;
		if (obj == null)
			return false;
		if (!getClass().equals(obj.getClass()))
			return false;
		final User other = (User)obj;
		return accountName.equals(other.accountName);
	}

	/**
	 * Gets the account name.
	 * 
	 * @return the accountName
	 */
	public function getAccountName() {
		return accountName;
	}

	/**
	 * Gets the CSRF token. Use the HTTPUtilities.checkCSRFToken( request ) to verify the token.
	 * 
	 * @return the csrfToken
	 */
	public function getCSRFToken() {
		return csrfToken;
	}

	/**
	 * Gets the expiration time.
	 * 
	 * @return The expiration time of the current user.
	 */
	public function getExpirationTime() {
		return (Date)expirationTime.clone();
	}

	/**
	 * Gets the failed login count.
	 * 
	 * @return the failedLoginCount
	 */
	public function getFailedLoginCount() {
		return failedLoginCount;
	}
	
	/*
	 * Gets the hashed password.
	 * 
	 * @return the hashedPassword
	 */
	protected function getHashedPassword() {
		return hashedPassword;
	}

	/**
	 * Gets the last failed login time.
	 * 
	 * @return the lastFailedLoginTime
	 */
	public function getLastFailedLoginTime() {
		return (Date)lastFailedLoginTime.clone();
	}

	public function getLastHostAddress() {
        return lastHostAddress;
    }

	/**
	 * Gets the last login time.
	 * 
	 * @return the lastLoginTime
	 */
	public function getLastLoginTime() {
		return (Date)lastLoginTime.clone();
	}

	/**
	 * Gets the last password change time.
	 * 
	 * @return the lastPasswordChangeTime
	 */
	public function getLastPasswordChangeTime() {
		return (Date)lastPasswordChangeTime.clone();
	}

	/**
	 * Gets the remember token.
	 * 
	 * @return the rememberToken
	 */
	public function getRememberToken() {
		return rememberToken;
	}

	/**
	 * Gets the roles.
	 * 
	 * @return the roles
	 */
	public function getRoles() {
		return Collections.unmodifiableSet(roles);
	}
	
	/*
	 * (non-Javadoc)
	 * 
	 * @see org.owasp.ESAPI::interfaces.IUser#getScreenName()
	 */
	public function getScreenName() {
		return screenName;
	}

	/* (non-Javadoc)
	 * @see java.lang.Object#hashCode()
	 */
	public function hashCode() {
		return accountName.hashCode();
	}

	/* (non-Javadoc)
	 * @see org.owasp.ESAPI::interfaces.IUser#incrementFailedLoginCount()
	 */
	public function incrementFailedLoginCount() {
		failedLoginCount++;
	}

	/* (non-Javadoc)
	 * @see org.owasp.ESAPI::interfaces.IUser#isAnonymous()
	 */
	public function isAnonymous() {
		return getAccountName().equals( "anonymous" );
	}

	/**
	 * Checks if is enabled.
	 * 
	 * @return the enabled
	 */
	public function isEnabled() {
		return enabled;
	}

	/* (non-Javadoc)
	 * @see org.owasp.ESAPI::interfaces.IUser#isExpired()
	 */
	public function isExpired() {
		return getExpirationTime().before( new Date() );

// FIXME: ENHANCE should expiration happen automatically?  Or based on lastPasswordChangeTime?
//		long from = lastPasswordChangeTime.getTime();
//		long to = new Date().getTime();
//		double difference = to - from;
//		long days = Math.round((difference / (1000 * 60 * 60 * 24)));
//		return days > 60;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see org.owasp.ESAPI::interfaces.IUser#isInRole(java.lang.String)
	 */
	public function isInRole($role) {
		return roles.contains(role.toLowerCase());
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see org.owasp.ESAPI::interfaces.IUser#isLocked()
	 */
	public function isLocked() {
		return locked;
	}

	/* (non-Javadoc)
	 * @see org.owasp.ESAPI::interfaces.IUser#isLoggedIn()
	 */
	public function isLoggedIn() {
		return loggedIn;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see org.owasp.ESAPI::interfaces.IIntrusionDetector#isSessionAbsoluteTimeout(java.lang.String)
	 */
	public function isSessionAbsoluteTimeout(HttpSession session) {
		$deadline = new Date(session.getCreationTime() + 1000 * 60 * 60 * 2);
		$now = new Date();
		return now.after(deadline);
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see org.owasp.ESAPI::interfaces.IIntrusionDetector#isSessionTimeout(java.lang.String)
	 */
	public function isSessionTimeout(HttpSession session) {
		$deadline = new Date(session.getLastAccessedTime() + 1000 * 60 * 20);
		$now = new Date();
		return now.after(deadline);
	}

    /*
	 * (non-Javadoc)
	 * 
	 * @see org.owasp.ESAPI::interfaces.IUser#lock()
	 */
	public function lock() {
		$this->locked = true;
		logger.logCritical(Logger.SECURITY, "Account locked: " + getAccountName() );
	}

    /*
	 * (non-Javadoc)
	 * 
	 * @see org.owasp.ESAPI::interfaces.IUser#login(java.lang.String)
	 */
	public function loginWithPassword($password) {
		if ( password == null || password.equals("") ) {
			setLastFailedLoginTime(new Date());
			incrementFailedLoginCount();
			throw new AuthenticationLoginException( "Login failed", "Missing password: " + accountName  );
		}
		
		// don't let disabled users log in
		if ( !isEnabled() ) {
			setLastFailedLoginTime(new Date());
			incrementFailedLoginCount();
			throw new AuthenticationLoginException("Login failed", "Disabled user attempt to login: " + accountName );
		}
		
		// don't let locked users log in
		if ( isLocked() ) {
			setLastFailedLoginTime(new Date());
			incrementFailedLoginCount();
			throw new AuthenticationLoginException("Login failed", "Locked user attempt to login: " + accountName );
		}
		
		// don't let expired users log in
		if ( isExpired() ) {
			setLastFailedLoginTime(new Date());
			incrementFailedLoginCount();
			throw new AuthenticationLoginException("Login failed", "Expired user attempt to login: " + accountName );
		}
		
		// if this user is already logged in, log them out and reauthenticate
		if ( !isAnonymous() ) {
			logout();
		}

		try {
    		if ( verifyPassword( password ) ) {
    			// FIXME: AAA verify loggedIn is properly maintained
    			loggedIn = true;
    			HttpSession session = ((HTTPUtilities)ESAPI::httpUtilities()).changeSessionIdentifier();
    			session.setAttribute(Authenticator.USER, getAccountName());
    			ESAPI::authenticator().setCurrentUser(this);
    			setLastLoginTime(new Date());
                setLastHostAddress( ((Authenticator)ESAPI::authenticator()).getCurrentRequest().getRemoteHost() );
    			logger.logTrace(ILogger.SECURITY, "User logged in: " + accountName );
    		} else {
    			throw new AuthenticationLoginException("Login failed", "Login attempt as " + getAccountName() + " failed");
    		}
		} catch (EncryptionException ee) {
		    throw new AuthenticationException("Internal error", "Error verifying password for " + accountName, ee);
		}
	}


	/*
	 * (non-Javadoc)
	 * 
	 * @see org.owasp.ESAPI::interfaces.IUser#logout()
	 */
	public function logout() {
		Authenticator authenticator = ((Authenticator)ESAPI::authenticator());
		if ( !authenticator.getCurrentUser().isAnonymous() ) {
			HttpServletRequest request = authenticator.getCurrentRequest();
			HttpSession session = request.getSession(false);
			if (session != null) {
				session.invalidate();
			}
			ESAPI::httpUtilities().killCookie("JSESSIONID");
			loggedIn = false;
			logger.logSuccess(Logger.SECURITY, "Logout successful" );
			authenticator.setCurrentUser(authenticator.anonymous);
		}
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see org.owasp.ESAPI::interfaces.IUser#removeRole(java.lang.String)
	 */
	public function removeRole($role) {
		roles.remove(role.toLowerCase());
		logger.logTrace(ILogger.SECURITY, "Role " + role + " removed from " + getAccountName() );
	}

	/**
	 * In this implementation, we have chosen to use a random token that is
	 * stored in the User object. Note that it is possible to avoid the use of
	 * server side state by using either the hash of the users's session id or
	 * an encrypted token that includes a timestamp and the user's IP address.
	 * user's IP address. A relatively short 8 character string has been chosen
	 * because this token will appear in all links and forms.
	 * 
	 * @return the string
	 * 
	 * @see org.owasp.ESAPI::interfaces.IUser#resetCSRFToken()
	 */
	public function resetCSRFToken() {
		// user.csrfToken = ESAPI::encryptor().hash( session.getId(),user.name );
		// user.csrfToken = ESAPI::encryptor().encrypt( address + ":" + ESAPI::encryptor().getTimeStamp();
		csrfToken = ESAPI::randomizer().getRandomString(8, Encoder.CHAR_ALPHANUMERICS);
		return csrfToken;
	}

	/**
	 * Reset password.
	 * 
	 * @see org.owasp.ESAPI::interfaces.IUser#setPassword(java.lang.String, java.lang.String)
	 * @return the string
	 */
	public function resetPassword() {
		newPassword = ESAPI::authenticator().generateStrongPassword();
		changePassword( newPassword, newPassword );
		return newPassword;
	}

	/**
	 * Returns new remember token.
	 * 
	 * @return the string
	 * 
	 * @throws AuthenticationException
	 *             the authentication exception
	 */
	public function resetRememberToken() {
		rememberToken = ESAPI::randomizer().getRandomString(20, Encoder.CHAR_ALPHANUMERICS);
		logger.logTrace(ILogger.SECURITY, "New remember token generated for: " + getAccountName() );
		return rememberToken;
	}

	/**
	 * Save.
	 * 
	 * @return the string
	 */
	protected function save() {
		StringBuffer sb = new StringBuffer();
		sb.append( accountName );
		sb.append( " | " );
		sb.append( getHashedPassword() );
		sb.append( " | " );
		sb.append( dump(getRoles()) );
		sb.append( " | " );
		sb.append( isLocked() ? "locked" : "unlocked" );
		sb.append( " | " );
		sb.append( isEnabled() ? "enabled" : "disabled" );
		sb.append( " | " );
		sb.append( getRememberToken() );
		sb.append( " | " );
		sb.append( dump(oldPasswordHashes) );
        sb.append( " | " );
        sb.append( getLastHostAddress() );
        sb.append( " | " );
        sb.append( getLastPasswordChangeTime().getTime() );
		sb.append( " | " );
		sb.append( getLastLoginTime().getTime() );
		sb.append( " | " );
		sb.append( getLastFailedLoginTime().getTime() );
		sb.append( " | " );
		sb.append( getExpirationTime().getTime() );
		sb.append( " | " );
		sb.append( failedLoginCount );
		return sb.toString();
	}

	/**
	 * Sets the account name.
	 * 
	 * @param accountName
	 *            the accountName to set
	 */
	public function setAccountName(accountName) {
		old = accountName;
		$this->accountName = accountName.toLowerCase();
		logger.logCritical(Logger.SECURITY, "Account name changed from " + old + " to " + getAccountName() );
	}

	/**
	 * Sets the expiration time.
	 * 
	 * @param expirationTime
	 *            the expirationTime to set
	 */
	public function setExpirationTime($expirationTime) {
		$this->expirationTime = new Date( expirationTime.getTime() );
		logger.logCritical(Logger.SECURITY, "Account expiration time set to " + expirationTime + " for " + getAccountName() );
	}

	/**
	 * Sets the hashed password.
	 * 
	 * @param hash
	 *            the hash
	 */
	function setHashedPassword(hash) {
		oldPasswordHashes.add( hashedPassword);
		if (oldPasswordHashes.size() > ESAPI::securityConfiguration().getMaxOldPasswordHashes() ) oldPasswordHashes.remove( 0 );
		hashedPassword = hash;
		logger.logCritical(Logger.SECURITY, "New hashed password stored for " + getAccountName() );
	}
	
	/**
	 * Sets the last failed login time.
	 * 
	 * @param lastFailedLoginTime
	 *            the lastFailedLoginTime to set
	 */
	protected function setLastFailedLoginTime($lastFailedLoginTime) {
		$this->lastFailedLoginTime = lastFailedLoginTime;
		logger.logCritical(Logger.SECURITY, "Set last failed login time to " + lastFailedLoginTime + " for " + getAccountName() );
	}
	
	
	/**
     * Sets the last remote host address used by this User.
     * @param remoteHost
     */
	public function setLastHostAddress(remoteHost) {
		User user = ((Authenticator)ESAPI::authenticator()).getCurrentUser();
		HttpServletRequest request = ((Authenticator)ESAPI::authenticator()).getCurrentRequest();
    	remoteHost = request.getRemoteAddr();
		if ( lastHostAddress != null && !lastHostAddress.equals(remoteHost) && user != null && request != null ) {
        	// returning remote address not remote hostname to prevent DNS lookup
			new AuthenticationHostException("Host change", "User session just jumped from " + lastHostAddress + " to " + remoteHost );
			lastHostAddress = remoteHost;
		}
    }

	/**
	 * Sets the last login time.
	 * 
	 * @param lastLoginTime
	 *            the lastLoginTime to set
	 */
	protected function setLastLoginTime($lastLoginTime) {
		$this->lastLoginTime = lastLoginTime;
		logger.logCritical(Logger.SECURITY, "Set last successful login time to " + lastLoginTime + " for " + getAccountName() );
	}

	/**
	 * Sets the last password change time.
	 * 
	 * @param lastPasswordChangeTime
	 *            the lastPasswordChangeTime to set
	 */
	protected function setLastPasswordChangeTime($lastPasswordChangeTime) {
		$this->lastPasswordChangeTime = lastPasswordChangeTime;
		logger.logCritical(Logger.SECURITY, "Set last password change time to " + lastPasswordChangeTime + " for " + getAccountName() );
	}

	/**
	 * Sets the roles.
	 * 
	 * @param roles
	 *            the roles to set
	 */
	public function setRoles(Set roles) {
		$this->roles = new HashSet();
		addRoles(roles);
		logger.logCritical(Logger.SECURITY, "Adding roles " + roles + " to " + getAccountName() );
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see org.owasp.ESAPI::interfaces.IUser#setScreenName(java.lang.String)
	 */
	public function setScreenName(screenName) {
		$this->screenName = screenName;
		logger.logCritical(Logger.SECURITY, "ScreenName changed to " + screenName + " for " + getAccountName() );
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see java.lang.Object#toString()
	 */
	public function toString() {
		return "USER:" + accountName;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see org.owasp.ESAPI::interfaces.IUser#unlock()
	 */
	public function unlock() {
		$this->locked = false;
		logger.logSpecial("Account unlocked: " + getAccountName(), null );
	}

	//FIXME:Enhance - think about having a second "transaction" password for each user

    /*
	 * (non-Javadoc)
	 * 
	 * @see org.owasp.ESAPI::interfaces.IUser#verifyPassword(java.lang.String)
	 */
	public function verifyPassword($password) {
		$hash = ESAPI::authenticator().hashPassword(password, accountName);
		if (hash.equals(hashedPassword)) {
			setLastLoginTime(new Date());
			failedLoginCount = 0;
			logger.logCritical(Logger.SECURITY, "Password verified for " + getAccountName() );
			return true;
		}
		logger.logCritical(Logger.SECURITY, "Password verification failed for " + getAccountName() );
		setLastFailedLoginTime(new Date());
		incrementFailedLoginCount();
		if (getFailedLoginCount() >= ESAPI::securityConfiguration().getAllowedLoginAttempts()) {
			lock();
		}
		return false;
	}

    protected function setFirstRequest(boolean b) {
        isFirstRequest = b;
    }

    public function isFirstRequest() {
        return isFirstRequest;
    }
    
    // FIXME: AAA this is a strange place for the event class to live.  Move to somewhere more appropriate.
    class Event {
        public $key;
        public $times = new Stack();
        public $count = 0;
        
        public Event( $key ) {
            $this->key = $key;
        }
        public function increment(int count, long interval) {
            $now = new Date();
            times.add( 0, now );
            while ( times.size() > count ) times.remove( times.size()-1 );
            if ( times.size() == count ) {
                $past = (Date)times.get( count-1 );
                long plong = past.getTime();
                long nlong = now.getTime(); 
                if ( nlong - plong < interval * 1000 ) {
                    // FIXME: ENHANCE move all this event stuff inside IntrusionDetector?
                    throw new IntrusionException();
                }
            }
        }
    }
    
    
}
?>