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
 * @author 
 * @created 2008
 * @since 1.4
 * @package org.owasp.esapi
 */

require_once dirname(__FILE__).'/errors/AuthenticationException.php';
require_once dirname(__FILE__).'/errors/EncryptionException.php';

/**
 * The Authenticator interface defines a set of methods for generating and
 * handling account credentials and session identifiers. The goal of this
 * interface is to encourage developers to protect credentials from disclosure
 * to the maximum extent possible.
 * <P>
 * <img src="doc-files/Authenticator.jpg">
 * <P>
 * One possible implementation relies on the use of a thread local variable to
 * store the current user's identity. The application is responsible for calling
 * setCurrentUser() as soon as possible after each HTTP request is received. The
 * value of getCurrentUser() is used in several other places in this API. This
 * eliminates the need to pass a user object to methods throughout the library.
 * For example, all of the logging, access control, and exception calls need
 * access to the currently logged in user.
 * <P>
 * The goal is to minimize the responsibility of the developer for
 * authentication. In this example, the user simply calls authenticate with the
 * current request and the name of the parameters containing the username and
 * password. The implementation should verify the password if necessary, create
 * a session if necessary, and set the user as the current user.
 * 
 * <pre>
 * public void doPost(ServletRequest request, ServletResponse response) {
 * try {
 * User user = ESAPI.authenticator().login(request, response);
 * // continue with authenticated user
 * } catch (AuthenticationException e) {
 * // handle failed authentication (it's already been logged)
 * }
 * </pre>
 * 
 * @author 
 * @since 1.4
 */
interface Authenticator {

	/**
	 * Clears the current User. This allows the thread to be reused safely.
     * 
     * This clears all threadlocal variables from the thread. This should ONLY be called after
     * all possible ESAPI operations have concluded. If you clear too early, many calls will
     * fail, including logging, which requires the user identity.  
	 */
	function clearCurrent();

	/**
	 * This method should be called for every HTTP request, to login the current user either from the session of HTTP
     * request. This method will set the current user so that getCurrentUser() will work properly. 
	 * 
	 * Authenticates the user's credentials from the HttpServletRequest if
	 * necessary, creates a session if necessary, and sets the user as the
	 * current user.
	 * 
	 * Specification:  The implementation should do the following:
     * 	1) Check if the User is already stored in the session
     * 		a. If so, check that session absolute and inactivity timeout have not expired
     * 		b. Step 2 may not be required if 1a has been satisfied
     * 	2) Verify User credentials
     * 		a. It is recommended that you use 
     * 			loginWithUsernameAndPassword(HttpServletRequest, HttpServletResponse) to verify credentials
     * 	3) Set the last host of the User (ex.  user.setLastHostAddress(address) )
     * 	4) Verify that the request is secure (ex. over SSL)
     * 	5) Verify the User account is allowed to be logged in
     * 		a. Verify the User is not disabled, expired or locked
     * 	6) Assign User to session variable      	
	 * 
	 * @param request
	 *            the current HTTP request
	 * @param response
	 *            the HTTP response
	 * 
	 * @return 
	 * 		the User
	 * 
	 * @throws AuthenticationException
	 *             if the credentials are not verified, or if the account is disabled, locked, expired, or timed out
	 */
	function login($request, $response);

	/**
	 * Verify that the supplied password matches the password for this user. Password should
	 * be stored as a hash. It is recommended you use the hashPassword(password, accountName) method
	 * in this class.
	 * This method is typically used for "reauthentication" for the most sensitive functions, such
	 * as transactions, changing email address, and changing other account information.
	 * 
	 * @param user 
	 * 		the user who requires verification
	 * @param password 
	 * 		the hashed user-supplied password
	 * 
	 * @return 
	 * 		true, if the password is correct for the specified user
	 */
	function verifyPassword($user, $password);
	
	/**
	 * Logs out the current user.
	 * 
	 * This is usually done by calling User.logout on the current User. 
	 */
    function logout();

	/**
	 * Creates a new User with the information provided. Implementations should check
	 * accountName and password for proper format and strength against brute force 
	 * attacks ( verifyAccountNameStrength(String), verifyPasswordStrength(String, String)  ).
	 * 
	 * Two copies of the new password are required to encourage user interface designers to
	 * include a "re-type password" field in their forms. Implementations should verify that 
	 * both are the same. 
	 * 
	 * @param accountName 
	 * 		the account name of the new user
	 * @param password1 
	 * 		the password of the new user
	 * @param password2 
	 * 		the password of the new user.  This field is to encourage user interface designers to include two password fields in their forms.
	 * 
	 * @return 
	 * 		the User that has been created 
	 * 
	 * @throws AuthenticationException 
	 * 		if user creation fails due to any of the qualifications listed in this method's description
	 */
	function createUser($accountName, $password1, $password2);

	/**
	 * Generate strong password that takes into account the user's information and old password. Implementations
	 * should verify that the new password does not include information such as the username, fragments of the
	 * old password, and other information that could be used to weaken the strength of the password.
	 * 
	 * @param user 
	 * 		the user whose information to use when generating password
	 * @param oldPassword 
	 * 		the old password to use when verifying strength of new password.  The new password may be checked for fragments of oldPassword.
	 * 
	 * @return 
	 * 		a password with strong password strength
	 */
	function generateStrongPassword($user = null, $oldPassword = null);

	/**
	 * Changes the password for the specified user. This requires the current password, as well as 
	 * the password to replace it with. The new password should be checked against old hashes to be sure the new password does not closely resemble or equal any recent passwords for that User.
	 * Password strength should also be verified.  This new password must be repeated to ensure that the user has typed it in correctly.
	 * 
	 * @param user 
	 * 		the user to change the password for
	 * @param currentPassword 
	 * 		the current password for the specified user
	 * @param newPassword 
	 * 		the new password to use
	 * @param newPassword2 
	 * 		a verification copy of the new password
	 * 
	 * @throws AuthenticationException 
	 * 		if any errors occur
	 */
	function changePassword($user, $currentPassword, $newPassword, $newPassword2);
	
	/**
	 * Returns the User matching the provided accountId.  If the accoundId is not found, an Anonymous
	 * User or null may be returned.
	 * 
	 * @param accountId
	 *            the account id
	 * 
	 * @return 
	 * 		the matching User object, or the Anonymous User if no match exists
	 */
	function getUserById($accountId);
		
	/**
	 * Returns the User matching the provided accountName.  If the accoundId is not found, an Anonymous
	 * User or null may be returned.
	 * 
	 * @param accountName
	 *            the account name
	 * 
	 * @return 
	 * 		the matching User object, or the Anonymous User if no match exists
	 */
	function getUserByName($accountName);

	/**
	 * Gets a collection containing all the existing user names.
	 * 
	 * @return 
	 * 		a set of all user names
	 */
	function getUserNames();

	/**
	 * Returns the currently logged in User.
	 * 
	 * @return 
	 * 		the matching User object, or the Anonymous User if no match
	 *         exists
	 */
	function getCurrentUser();

	/**
	 * Sets the currently logged in User.
	 * 
	 * @param user
	 *          the user to set as the current user
	 */
	function setCurrentUser($user);

	/**
	 * Returns a $representation of the hashed password, using the
	 * accountName as the salt. The salt helps to prevent against "rainbow"
	 * table attacks where the attacker pre-calculates hashes for known strings.
	 * This method specifies the use of the user's account name as the "salt"
	 * value. The Encryptor.hash method can be used if a different salt is
	 * required.
	 * 
	 * @param password
	 *            the password to hash
	 * @param accountName
	 *            the account name to use as the salt
	 * 
	 * @return 
	 * 		the hashed password
	 */
	function hashPassword($password, $accountName);

	/**
	 * Removes the account of the specified accountName.
	 * 
	 * @param accountName
	 *            the account name to remove
	 * 
	 * @throws AuthenticationException
	 *             the authentication exception if user does not exist
	 */
	function removeUser($accountName);

	/**
	 * Ensures that the account name passes site-specific complexity requirements, like minimum length.
	 * 
	 * @param accountName
	 *            the account name
	 * 
	 * @throws AuthenticationException
	 *             if account name does not meet complexity requirements
	 */
	function verifyAccountNameStrength($accountName);

	/**
	 * Ensures that the password meets site-specific complexity requirements, like length or number 
	 * of character sets. This method takes the old password so that the algorithm can analyze the 
	 * new password to see if it is too similar to the old password. Note that this has to be
	 * invoked when the user has entered the old password, as the list of old
	 * credentials stored by ESAPI is all hashed.
	 * 
	 * @param oldPassword
	 *            the old password
	 * @param newPassword
	 *            the new password
	 * 
	 * @throws AuthenticationException
	 *				if newPassword is too similar to oldPassword or if newPassword does not meet complexity requirements
	 */
	function verifyPasswordStrength($oldPassword, $newPassword);

	/**
	 * Determine if the account exists.
	 * 
	 * @param accountName
	 *            the account name
	 * 
	 * @return true, if the account exists
	 */
	function exists($accountName);

}
?>