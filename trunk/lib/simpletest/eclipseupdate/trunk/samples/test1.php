<?php
class test1 extends UnitTestCase {
	function test_pass(){
		$x = 1;
		$y = 2;
		$total = $x + $y;
		$this->assertEqual(3,$total, "This should pass");
	}
}
?>
