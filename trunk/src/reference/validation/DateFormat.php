<?php
/**
 * 
 * @package ESAPI_Reference_Validation
 *
 */
class DateFormat {
	private $format = array();
	const types = array('SMALL','MEDIUM','LONG','FULL');
	
	function __construct($format=null, $type='MEDIUM') {
		$this->setformat($format,$type);
	}
	
	function setformat($format, $type='MEDIUM') {
		
		if ( is_array($format)) {
			foreach ( self::types as $t ) {
				if ( key_exists($t, $format)) {
					$this->format[$t] = $format[$t];		
				}
			}
		} else {
			if ( in_array($type, self::types) ) {
				$this->format[$type] = $format;
			} else {
				throw ValidationException("invalid date type " . $type);
			}						
		}
	}
}

?>