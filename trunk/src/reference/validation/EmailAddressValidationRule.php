<?php
/**
 * OWASP Enterprise Security API (ESAPI)
 *
 * This file is part of the Open Web Application Security Project (OWASP)
 * Enterprise Security API (ESAPI) project.
 *
 * LICENSE: This source file is subject to the New BSD license.  You should read
 * and accept the LICENSE before you use, modify, and/or redistribute this
 * software.
 *
 * @category  OWASP
 * @package   ESAPI
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */


/**
 * EmailAddressValidationRule requires the BaseValidationRule.
 */
require_once dirname(__FILE__) . '/BaseValidationRule.php';


/**
 * EmailAddressValidationRule implementation of the ValidationRule interface.
 *
 * PHP version 5.2.9
 *
 * @category  OWASP
 * @package   ESAPI
 * @version   1.0
 * @author    Mike Boberski <boberski_michael@bah.com>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */

class EmailAddressValidationRule extends StringValidationRule
{
    private $logger   = null;
 
    /**
     * Constructor sets-up the validation rule with a descriptive name for this
     * validator, an optional Encoder instance (for canonicalization) and an
     * optional whitelist regex pattern to validate the input against prior to
     * email address purification.
     * An instance of the HTMLPurifier class is created and stored too.
     *
     * @param  $typeName string descriptive name for this validator.
     * @param  $encoder object providing canonicalize method.
     * @param  $whiteListPattern string whitelist regex.
     */
    public function __construct($typeName, $encoder = null, $whitelistPattern = null)
    {
        global $ESAPI;

        parent::__construct($typeName, $encoder);

        $this->logger = $ESAPI->getAuditor("EmailAddressValidationRule");
    }

    /**
     * Returns the canonicalized, valid input.
     * Throws ValidationException if the input is not valid or
     * IntrusionException if the input is an obvious attack.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g., ProfilePage_Signature). This value is used by
     *         any logging or error handling that is done with respect to the
     *         value passed in.
     * @param  $input The actual string user input data to validate.
     *
     * @return string canonicalized, valid input.
     *
     * @throws ValidationException, IntrusionException
     */
    public function getValid($context, $input)
    {
        // Parent validator will sanity check.
        $canonical = parent::getValid($context, $input);

        $clean_email = filter_var($canonical, FILTER_SANITIZE_EMAIL);
        if($clean_email == FALSE)
        {
            throw new ValidationException(
                'Email Address Input is not valid.',
                'Error attempting to sanitize Email Address: '. $input,
                $context
            );
        }

        if (strcmp($canonical, $clean_email) !== 0)
        {
            throw new ValidationException(
                'Email Address Input may not be valid.',
                'Resorted to string comparsion of canonicalized and purified Email Address input - result was Not Equal',
                $context
            );
        }

        return $clean_email;
    }


    /**
     * Simply attempt to purify the email address and return an empty string if that
     * fails.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g., ProfilePage_Signature). This value is used by
     *         any logging or error handling that is done with respect to the
     *         value passed in.
     * @param  $input The actual user input data to validate.
     *
     * @return string purified email address or en empty string.
     */
    public function sanitize($context, $input)
    {
    	$clean_email = filter_var($input, FILTER_SANITIZE_EMAIL);
    	if($clean_email == FALSE)
    	{
    		return "";
    	}
    	else
    	{
        	return $clean_email;
    	}
    }

}
