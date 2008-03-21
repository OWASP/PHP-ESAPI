<?php
    // $Id: simpletest_test.php 1097 2005-08-16 03:32:11Z lastcraft $
    require_once(dirname(__FILE__) . '/../simpletest.php');
        
    SimpleTest::ignore('ShouldNeverBeRun');
    class ShouldNeverBeRun extends UnitTestCase {
        function testWithNoChanceOfSuccess() {
            $this->fail('Should be ignored');
        }
    }
?>