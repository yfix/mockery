<?php
/**
 * Mockery
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://github.com/padraic/mutateme/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to padraic@php.net so we can send you a copy immediately.
 *
 * @category   Mockery
 * @package    Mockery
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2012 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    http://github.com/padraic/mockery/blob/master/LICENSE New BSD License
 */

/**
 * The stub method on a mock offers an alternative syntax to shouldReceive, 
 * which infers the user is creating an mock expectation, when it actually 
 * defaults to a stub
 */
class Mockery_StubsTest extends PHPUnit_Framework_TestCase
{

    public function setup ()
    {
        $this->container = new \Mockery\Container;
    }
    
    public function teardown()
    {
        $this->container->mockery_close();
    }

    /**
     * @test
     */
    public function stubShouldReturnNull()
    {
        $mock = $this->container->mock();
        $mock->stub("getNull");
        $this->assertEquals(null, $mock->getNull());
    }

    /**
     * @test
     */
    public function stubShouldReturnValue()
    {
        $mock = $this->container->mock();
        $mock->stub("getDave", "dave");
        $this->assertEquals("dave", $mock->getDave());
    }

    /**
     * @test
     */
    public function stubShouldReturnOutputOfPassedClosure()
    {
        $mock = $this->container->mock();
        $mock->stub("getDave", function() { return "dave";} );
        $this->assertEquals("dave", $mock->getDave());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function stubShouldAllowThrowingInClosure()
    {
        $mock = $this->container->mock();
        $mock->stub("getDave", function() { throw new \InvalidArgumentException(); });
        $mock->getDave();
    }

    /**
     * @test
     */
    public function stubShouldNotFailVerificationIfNotInvoked()
    {
        $mock = $this->container->mock();
        $mock->stub("getDave", function() { return "dave";} );
        $this->container->mockery_close();
    }

    /**
     * @test
     */
    public function stubShouldRespectSpecificArguments()
    {
        $mock = $this->container->mock();
        $mock->stub("getDave")->with("Marshall")->andReturn("Dave Marshall");
        $mock->stub("getDave")->with("Smith")->andReturn("Dave Smith");
        $this->assertEquals("Dave Smith", $mock->getDave("Smith"));
        $this->assertEquals("Dave Marshall", $mock->getDave("Marshall"));
    }

    /**
     * @test
     */
    public function stubShouldRespectArgumentMatchers()
    {
        $mock = $this->container->mock();
        $mock->stub("getDave")->with(\Mockery::any())->andReturn("Dave Marshall");
        $this->assertEquals("Dave Marshall", $mock->getDave(123));
    }
}

