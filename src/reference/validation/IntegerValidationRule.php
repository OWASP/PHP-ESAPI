<?php

class IntegerValidationRule extends BaseValidationRule {
	
	private $minValue;
	private $maxValue;
	


	public function IntegerValidationRule( $typeName, $encoder, $minValue=0, $maxValue=PHP_MAX_INT ) {
		parent::BaseValidationRule( $typeName, $encoder );
		$this->minValue = $minValue;
		$this->maxValue = $maxValue;
	}

	public function getValid( $context, $input,$errorlist=null )  {

		// check null
	    if ( strlen($input)==0 ) {
			if ($this->allowNull) return null;
			throw new ValidationException( $context . ": Input number required", "Input number required: context=".$context.", input=".$input, $context );
	    }
	    
	    // canonicalize
	    $canonical = null;
	    try {
	    	$canonical = $this->encoder->canonicalize($input);
	    } catch (EncodingException $e) {
	        throw new ValidationException( $context.": Invalid number input. Encoding problem detected.", "Error canonicalizing user input", $e, $context);
	    }

		if ($this->minValue > $this->maxValue) {
			throw new ValidationException( $context.": Invalid number input: context", "Validation parameter error for number: maxValue ( ".$this->maxValue.") must be greater than minValue ( ".$this->minValue . ") for ".$context, $context );
		}
		
		// validate min and max
		try {
			$i = $canonical;
			if ($i!=intval($i)) throw new ValidationException("Invalid integer ".$i,"Invalid integer".$i,$context);
			if ($i < $this->minValue) throw new ValidationException( "Invalid integer input must be between ".$this->minValue." and ".$this->maxValue.": context=".$context."Invalid integer input must be between ".$this->minValue." and ".$this->maxValue.": context=".$context.", input=".$input, $context );
			if ($i > $this->maxValue) throw new ValidationException( "Invalid integer input must be between ".$this->minValue." and ".$this->maxValue.": context=".$context."Invalid integer input must be between ".$this->minValue." and ".$this->maxValue.": context=".$context.", input=".$input, $context );
			return $i;
		} catch (NumberFormatException $e) {
			throw new ValidationException( $context . ": Invalid integer input", "Invalid integer input format: context=".$context.", input=".$input, $e, $context);
		}
	}

	public function sanitize( $context, $input ) {
		return 0;
	}
	
}

?>