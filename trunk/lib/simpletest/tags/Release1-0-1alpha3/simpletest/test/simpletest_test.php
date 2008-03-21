<?php
    // $Id: simpletest_test.php 1221 2006-01-01 23:16:57Z lastcraft $
    require_once(dirname(__FILE__) . '/../simpletest.php');

    SimpleTest::ignore('ShouldNeverBeRunEither');

    class ShouldNeverBeRun extends UnitTestCase {
        function testWithNoChanceOfSuccess() {
            $this->fail('Should be ignored');
        }
    }

    class ShouldNeverBeRunEither extends ShouldNeverBeRun { }
?>