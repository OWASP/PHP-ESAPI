<?php
/**
 * A validator performs syntax and possibly semantic validation of a single
 * piece of data from an untrusted source.
 * 
 * @author Jeff Williams (jeff.williams .at. aspectsecurity.com) <a
 *         href="http://www.aspectsecurity.com">Aspect Security</a>
 * @since June 1, 2007
 * @see org.owasp.esapi.Validator
 */
class DateValidationRule extends BaseValidationRule {
	
	private $format;
	
	public function DateValidationRule( $typeName, $encoder, $newFormat ) {
		parent::BaseValidationRule( $typeName, $encoder );      
		if ( $newFormat=='' ) {
			$newFormat='%Y-%m-%d';
		}
		$this->setDateFormat( $newFormat );
	}
	
    public function setDateFormat( $newFormat ) {
        if ($newFormat == null) throw new RuntimeException("DateValidationRule.setDateFormat requires a non-null DateFormat");
        $this->format = $newFormat;
    }

	public function getValid( $context, $input,$errorlist=null )  {

		// check null
	    if ( strlen($input)==0 ) {
			if ($this->allowNull) return null;
			throw new ValidationException( $context.": Input date required", "Input date required: context=".$context.", input=".$input, $context );
	    }
	    
	    // canonicalize
	    $canonical = null;
	    try {
	    	$canonical = $this->encoder->canonicalize( $input );
	    } catch (EncodingException $e) {
	        throw new ValidationException( $context.": Invalid date input. Encoding problem detected.", "Error canonicalizing user input", $e, $context);
	    }

		try {			
			$date=date_create($canonical);
			// validation passed
			return $date;
		} catch (Exception $e) {
			throw new ValidationException( $context.": Invalid date must follow the ".$format->getNumberFormat() + " format", "Invalid date: context=".$context.", format=".$format.", input=".$input, $e, $context);
		}
	}
	
	public function sanitize( $context, $input )  {
		return date();
	}
	
}

?>