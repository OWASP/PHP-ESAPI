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
 * @author Johannes B. Ullrich
 * @created 2008
 * @since 1.4
 * @package org.owasp.esapi.reference
 */

class NumberValidationRule extends BaseValidationRule {
        
        private $minValue;
        private $maxValue;
        
        // the use of -1.#INF and 1.#INF attempts to emulate the java version. Not sure yet if it 
        // works TODO
        
        public function NumberValidationRule( $typeName, $encoder, $minValue='-1.#INF', $maxValue='1.#INF' ) {
              	parent::BaseValidationRule($typeName, $encoder);
                $this->minValue = $minValue;
                $this->maxValue = $maxValue;
        }
        public function getValid( $context, $input,$errorlist=null ) {

	       // check null
	       if ( strlen($input)==0 ) {
                  if ($this->allowNull) return null;
                     throw new ValidationException( $context.": Input number required", 
                        	"Input number required: context=".$context.", input=".$input, $context );
               }
            
            // canonicalize
            $canonical = null;
            try {
                $canonical = $this->encoder->canonicalize( $input );
            } catch (EncodingException $e) {
                throw new ValidationException( $context.": Invalid number input. Encoding problem detected.", 
                "Error canonicalizing user input", $e, $context);
            }

                if ($this->minValue > $this->maxValue) {
                        throw new ValidationException( $context.": Invalid number input: context", 
			                        "Validation parameter error for number: maxValue ( ".$maxValue.") must be greater than minValue ( ".$minValue.") for ".$context, $context );
                }
                
                // validate min and max
                try {
                   $d=$canonical;
                   if ( ! is_numeric($d)) 
                      throw new ValidationException("Invalid number input: context=".$context,
                                                    "Invalid double input is not numeric".$input, $context);

                   if ( is_infinite($d) ) 
                      throw new ValidationException( "Invalid number input: context=".$context, 
                	       	                     "Invalid double input is infinite: context=".$context.", input=".$input, $context );

                   if (is_nan($d)) 
		      throw new ValidationException( "Invalid number input: context=".$context, 
                                                     "Invalid double input is not a number: context=".$context.", input=".$input,$context );
                        if ($d < $this->minValue) 
			   throw new ValidationException( "Invalid number input must be between ".$minValue." and ".$maxValue.": context=".$context, "Invalid number input must be between ".$minValue." and ".$maxValue.": context=".$context.", input=".$input, $context );
                        if ($d > $this->maxValue) throw new ValidationException( "Invalid number input must be between ".$minValue." and ".$maxValue.": context=".$context, "Invalid number input must be between ".$minValue." and ".$maxValue.": context=".$context.", input=".$input, $context 
);                      
                        return $d;
        } catch (NumberFormatException $e) {
                        throw new ValidationException( $context.": Invalid number input", 
                        "Invalid number input format: context=".$context.", input=".$input, $e, $context);
                }
        }
        
        public function sanitize( $context, $input ) {
                return 0;
        }

}
                        
?>