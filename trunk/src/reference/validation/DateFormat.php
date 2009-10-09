<?php

class DateFormat {
	private $format=array();
	const private $types=array('SMALL','MEDIUM','LONG','FULL');
	function __construct($format=null,$type='MEDIUM') {
		Da
		$this->setformat($format,$type);
	}
	function setformat($format,$type='MEDIUM') {
		if ( is_array($format)) {
			foreach ( $this->types as $t ) {
				if ( key_exists($t,$format)) {
					$this->format[$t]=$format[$t];		
				}
			}
		} else {
			if ( in_array($type,$types) {
			$this->format[$type]=$format;
			} else {
				throw ValidationException("invalid date type ".$type);
			}						
		}
	}
}

?>