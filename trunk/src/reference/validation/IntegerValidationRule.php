<?php
public class IntegerValidationRule extends BaseValidationRule {
	
	private $minValue;
	private $maxValue;
	


	public IntegerValidationRule( String typeName, Encoder encoder, int minValue=0, int maxValue=PHP_MAX_INT ) {
		super( typeName, encoder );
		$this->minValue = $minValue;
		$this->maxValue = $maxValue;
	}

	public Object getValid( String context, String input ) throws ValidationException {

		// check null
	    if ( input == null || input.length()==0 ) {
			if (allowNull) return null;
			throw new ValidationException( context + ": Input number required", "Input number required: context=" + context + ", input=" + input, context );
	    }
	    
	    // canonicalize
	    String canonical = null;
	    try {
	    	canonical = encoder.canonicalize( input );
	    } catch (EncodingException e) {
	        throw new ValidationException( context + ": Invalid number input. Encoding problem detected.", "Error canonicalizing user input", e, context);
	    }

		if (minValue > maxValue) {
			throw new ValidationException( context + ": Invalid number input: context", "Validation parameter error for number: maxValue ( " + maxValue + ") must be greater than minValue ( " + minValue + ") for " + context, context );
		}
		
		// validate min and max
		try {
			Integer i = new Integer(canonical);
			if (i.intValue() < minValue) throw new ValidationException( "Invalid number input must be between " + minValue + " and " + maxValue + ": context=" + context, "Invalid number input must be between " + minValue + " and " + maxValue + ": context=" + context + ", input=" + input, context );
			if (i.intValue() > maxValue) throw new ValidationException( "Invalid number input must be between " + minValue + " and " + maxValue + ": context=" + context, "Invalid number input must be between " + minValue + " and " + maxValue + ": context=" + context + ", input=" + input, context );
			return i;
		} catch (NumberFormatException e) {
			throw new ValidationException( context + ": Invalid number input", "Invalid number input format: context=" + context + ", input=" + input, e, context);
		}
	}

	public Object sanitize( String context, String input ) {
		return Integer.valueOf( 0 );
	}
	
}

?>