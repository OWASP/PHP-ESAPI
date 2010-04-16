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
 * @package   ESAPI_Reference_Validation
 * @author    Jeff Williams <jeff.williams@aspectsecurity.com>
 * @author    Johannes B. Ullrich <jullrich@sans.edu>
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */


/**
 * IntegerValidationRule requires the BaseValidationRule.
 */
require_once dirname(__FILE__) . '/BaseValidationRule.php';


/**
 * IntegerValidationRule implementation of the ValidationRule interface.
 *
 * PHP version 5.2.9
 *
 * @category  OWASP
 * @package   ESAPI
 * @version   1.0
 * @author    Jeff Williams <jeff.williams@aspectsecurity.com>
 * @author    Johannes B. Ullrich <jullrich@sans.edu>
 * @author    jah <jah@jahboite.co.uk>
 * @copyright 2009-2010 The OWASP Foundation
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD license
 * @link      http://www.owasp.org/index.php/ESAPI
 */
class IntegerValidationRule extends BaseValidationRule
{
    private $minValue;
    private $maxValue;


    /**
     * Constructor sets-up the validation rule with a descriptive name for this
     * validator, an optional Encoder instance (for canonicalization) and
     * optional minimum and maximum bounds for valid integers.
     *
     * @param  $typeName string descriptive name for this validator.
     * @param  $encoder object providing canonicalize method.
     * @param  $minValue integer minimum valid number.
     * @param  $maxValue integer maximum valid number.
     */
    public function __construct($typeName, $encoder, $minValue = null, $maxValue = null)
    {
        parent::__construct($typeName, $encoder);

        if ($minValue === null || ! is_numeric($minValue)) {
            $this->minValue = 1 - PHP_INT_MAX;
        } else {
            $this->minValue = (int) $minValue;
        }
        if ($maxValue === null || ! is_numeric($maxValue)) {
            $this->maxValue = PHP_INT_MAX;
        } else {
            $this->maxValue = (int) $maxValue;
        }
    }


    /**
     * Returns the canonicalized, valid input.
     * Throws ValidationException if the input is not valid or
     * IntrusionException if the input is an obvious attack.
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g., LoginPage_UsernameField). This value is used by
     *         any logging or error handling that is done with respect to the
     *         value passed in.
     * @param  $input The actual string user input data to validate.
     *
     * @return integer parsed from canonicalized, valid input.
     * 
     * @throws ValidationException, IntrusionException
     */
    public function getValid($context, $input)
    {
        // Some sanity checks first
        if (! is_string($context)) {
            $context = 'NoContextSupplied'; // TODO Invalid Arg Exception?
        }
        if (! is_string($input) && $input !== null) {
            throw new ValidationException(
                "{$context}: Input required",
                "Input was not a string or NULL: context={$context}",
                $context
            );
        }
        if ($this->minValue > $this->maxValue) {
            throw new RuntimeException(
                'Validation misconfiguration - $minValue should not be greater than $maxValue!'
            );
        }
        if ($input === null || $input == '')
        {
            if ($this->allowNull) {
                return null;
            }
            throw new ValidationException(
                "{$context}: Input required",
                "Input required: context={$context}",
                $context
            );
        }

        // strict canonicalization
        $canonical = null;
        try
        {
            $canonical = $this->encoder->canonicalize($input, true);
        }
        catch (EncodingException $e)
        {
            throw new ValidationException(
                $context . ': Invalid input. Encoding problem detected.',
                'An EncodingException was thrown during canonicalization of the input.',
                $context
            );
        }

        // validate min and max
        try
        {
            if (! preg_match('/^[-+0-9]+$/', $canonical)) {
                throw new ValidationException(
                    'Invalid integer input: context=' . $context,
                    'Invalid integer input: Input is not a valid integer: ' . $input,
                    $context
                );
            }
            $i = $canonical;
            if ($i != intval($i)) {
                throw new ValidationException(
                    'Invalid integer input: context=' . $context,
                    'Invalid integer input: Input is not a valid integer: ' . $input,
                    $context
                );
            }
            $i = (int) $i;
            if ($i < $this->minValue) {
                throw new ValidationException(
                    'Invalid integer input must not be less than ' . $this->minValue,
                    'Invalid integer input must not be less than ' . $this->minValue . ': context=' . $context . ', input=' . $input,
                    $context
                );
            }
            if ($i > $this->maxValue) {
                throw new ValidationException(
                    'Invalid integer input must not be greater than ' . $this->maxValue,
                    'Invalid integer input must not be greater than ' . $this->maxValue . ': context=' . $context . ', input=' . $input,
                    $context
                );
            }
            return $i;
        }
        catch (NumberFormatException $e)
        {
            throw new ValidationException(
                $context . ': Invalid integer input',
                'Invalid integer input format: Caught NumberFormatException: ' . $e->getMessage() . 'context=' . $context . ', input=' . $input,
                $context
            );
        }
    }


    /**
     * Returns a default safe number - in this case zero.
     * TODO filter non-numeric chars 0123456789+- ?
     *
     * @param  $context A descriptive name of the parameter that you are
     *         validating (e.g., LoginPage_UsernameField). This value is used by
     *         any logging or error handling that is done with respect to the
     *         value passed in.
     * @param  $input The actual user input data to validate.
     *
     * @return zero - a dafault safe number.
     */
    public function sanitize($context, $input)
    {
        return 0;
    }

}
