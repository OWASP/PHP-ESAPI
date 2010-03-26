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
 * @author    Johannes B. Ullrich <jullrich@sans.edu>
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */


/**
 * DateValidationRule requires the BaseValidationRule and HTMLPurifier.
 */
require_once dirname(__FILE__) . '/BaseValidationRule.php';
require_once dirname(__FILE__) . '/../../../lib/htmlpurifier/HTMLPurifier.includes.php';


/**
 * DateValidationRule implementation of the ValidationRule interface.
 *
 * PHP version 5.2.9
 *
 * @category  OWASP
 * @package   ESAPI
 * @version   1.0
 * @author    Johannes B. Ullrich <jullrich@sans.edu>
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */

// TODO : configuration of htmlpurifier and logging
//
// $config->set('Core.Encoding', 'UTF-8'); // replace with your encoding
// $config->set('HTML.Doctype', 'XHTML 1.0 Transitional'); // replace with your doctype
// $config->set('Core.CollectErrors' , true);
//
//
class HTMLValidationRule extends StringValidationRule
{
    private $logger   = null;
    private $purifier = null;

    /**
     * Constructor sets-up the validation rule with a descriptive name for this
     * validator, an optional Encoder instance (for canonicalization) and an
     * optional whitelist regex pattern to validate the input against prior to
     * HTML purification.
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

        $this->logger = $ESAPI->getLogger("HTMLValidationRule");
        try
        {
            $this->purifier = new HTMLPurifier($this->basicConfig());
        }
        catch (Exception $e)
        {
            throw new ValidationException(
                'Could not initialize HTMLPurifier.',
                'Caught ' . gettype($e) . ' attempting to instantiate HTMLPurifier: '. $e->getMessage,
                'HTMLValidationRule->construct'
            );
        }
    }

// TODO : configuration of htmlpurifier
// Unless a proper config is present, the use of HTMLPurifier is pretty meaningless so what needs to happen
// is to define an example policy (in an ini) - something like a very restricted set of html tags, no javascript,
// safe css etc.
// Errors might need to make it back to the user (a la AntiSamy?) so the errorlist object needs implementing.
//
// $config->set('Core.Encoding', 'UTF-8'); // replace with your encoding
// $config->set('HTML.Doctype', 'XHTML 1.0 Transitional'); // replace with your doctype
// $config->set('Core', 'CollectErrors');

    /**
     * Returns some basic HTMLPurifier config directives - particularly the
     * errorCollector which we'll use to determine whether there were errors in
     * the HTML
     * TODO load an ini file.
     */
    private function basicConfig()
    {
        $a = array();
        $a['Core.Encoding'] = 'UTF-8';
        $a['HTML.Doctype'] = 'XHTML 1.0 Transitional';
        $a['HTML.ForbiddenAttributes'] = 'body@onload';
        $a['Core.CollectErrors'] = true;
        return $a;
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

        $clean_html = null;
        try
        {
            $clean_html = $this->purifier->purify($canonical);
        }
        catch (Exception $e)
        {
            throw new ValidationException(
                'HTML Input is not valid.',
                'Caught ' . gettype($e) . ' attempting to purify HTML: '. $e->getMessage,
                $context
            );
        }

        // If ErrorCollector was used, it may be able to tell us about errors in
        // the html.  If not, (poor quality) assumption is that if canonicalized
        // input and the output don't match then the input wasn't valid.
        $numErrors = 0;
        $errors = $this->purifier->context->get('ErrorCollector');
        if ($errors instanceof HTMLPurifier_ErrorCollector)
        {
            $numErrors = sizeof($errors->getRaw(), false);
            if ($numErrors > 0) {
                throw new ValidationException(
                    'HTML Input is not valid.',
                    "{$numErrors} found in HTML - Input is not valid.",
                    $context
                 );
            }
        }
        else if (strcmp($canonical, $clean_html) !== 0)
        {
            throw new ValidationException(
                'HTML Input may not be valid.',
                'Resorted to string comparsion of canonicalized and purified HTML input - result was Not Equal',
                $context
            );
        }

        return $clean_html;
    }


    /**
     * Simply attempt to purify the HTML and return an empty string if that
     * fails.
     * TODO this should sanitize based on a specific policy.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g., ProfilePage_Signature). This value is used by
     *         any logging or error handling that is done with respect to the
     *         value passed in.
     * @param  $input The actual user input data to validate.
     *
     * @return string purified HTML or en empty string.
     */
    public function sanitize($context, $input)
    {
        $clean_html = null;
        try
        {
            $clean_html = $this->purifier->purify($input);
        }
        catch (Exception $e)
        {
            // NoOp - return clean_html
        }
        return $clean_html;
    }

}
