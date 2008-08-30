<?php
require_once 'PHPUnit2/Framework/TestCase.php';
class phpunit2test extends PHPUnit2_Framework_TestCase  {
       public function testPass()
       {
            $this->assertEquals(1, 1);
       }

       public function testFail()
       {
           $this->assertEquals(2, 3);
           $this->assertEquals(1, 2);
       }

       public function testError()
       {
           throw new Exception("Error");
       }
}
?>