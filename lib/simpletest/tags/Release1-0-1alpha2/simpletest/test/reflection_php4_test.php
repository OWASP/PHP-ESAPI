<?php
    // $Id: reflection_php4_test.php 1115 2005-08-24 01:21:34Z lastcraft $
    
    class AnyOldThing {
        function aMethod() {
        }
    }

    class TestOfReflection extends UnitTestCase {
        
        function testClassExistence() {
            $reflection = new SimpleReflection('AnyOldThing');
            $this->assertTrue($reflection->classOrInterfaceExists());
            $this->assertTrue($reflection->classOrInterfaceExistsSansAutoload());
        }
        
        function testClassNonExistence() {
            $reflection = new SimpleReflection('UnknownThing');
            $this->assertFalse($reflection->classOrInterfaceExists());
            $this->assertFalse($reflection->classOrInterfaceExistsSansAutoload());
        }
        
        function testMethodsListFromClass() {
            $reflection = new SimpleReflection('AnyOldThing');
            $methods = $reflection->getMethods();
            $this->assertEqualIgnoringCase($methods[0], 'aMethod');
        }
        
        function testNoInterfacesForPHP4() {
            $reflection = new SimpleReflection('AnyOldThing');
        	$this->assertEqual(
        			$reflection->getInterfaces(),
        			array());
        }
        
        function testMostGeneralPossibleSignature() {
            $reflection = new SimpleReflection('AnyOldThing');
        	$this->assertEqualIgnoringCase(
        			$reflection->getSignature('aMethod'),
        			'function &aMethod()');
        }
        
        function assertEqualIgnoringCase($a, $b) {
            return $this->assertEqual(strtolower($a), strtolower($b));
        }
    }
?>