<?php
class test2 extends UnitTestCase {	
	function test_pass(){
		$x = 1;
		$y = 2;
		$total = $x + $y;
		$this->assertEqual(3,$total, "This should pass");
	}
	function test_fail(){
		$x = 1;
		$y = 2;
		$total = $x + $y;
		$this->assertEqual(4,$total,"This should fail");
		}
}
?>