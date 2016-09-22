<?php
/**
 * Caridea
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 *
 * @copyright 2015-2016 LibreWorks contributors
 * @license   http://opensource.org/licenses/Apache-2.0 Apache 2.0 License
 */
namespace Caridea\Validate;

/**
 * Generated by hand
 */
class RegistryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Registry
     */
    protected $object;

    /**
     * Sets up the fixture
     */
    protected function setUp()
    {
        $this->object = new Registry();
    }

    /**
     * @covers Caridea\Validate\Registry::__construct
     * @covers Caridea\Validate\Registry::register
     */
    public function testRegister()
    {
        $this->object->register(['test' => function ($b, $c) {
            $this->assertEquals('foo', $b);
            $this->assertEquals('bar', $c);
            return Rule\Compare::max(123);
        }]);
        $f = $this->object->factory('test', ['foo', 'bar']);
        $this->assertInstanceOf(Rule\Compare::class, $f);
    }

    /**
     * @covers Caridea\Validate\Registry::register
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Values passed to register must be callable
     */
    public function testRegisterBad()
    {
        $this->object->register(['test' => 123]);
    }

    /**
     * @covers Caridea\Validate\Registry::__construct
     * @covers Caridea\Validate\Registry::factory
     */
    public function testFactory()
    {
        $f = $this->object->factory('required', []);
        $this->assertInstanceOf(Rule::class, $f);
    }

    /**
     * @covers Caridea\Validate\Registry::__construct
     * @covers Caridea\Validate\Registry::factory
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage No rule registered with name: foobar
     */
    public function testFactoryMissing()
    {
        $this->object->factory('foobar', []);
    }

    /**
     * @covers Caridea\Validate\Registry::__construct
     * @covers Caridea\Validate\Registry::factory
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Definitions must return Rule objects
     */
    public function testFactoryUncallable()
    {
        $this->object->register(['foobar' => function () {
            return 'hi';
        }]);
        $this->object->factory('foobar', []);
    }

    /**
     * @covers Caridea\Validate\Registry::builder
     */
    public function testBuilder()
    {
        $registry = new Registry();
        $builder = $registry->builder();
        $this->assertInstanceOf(Builder::class, $builder);
        $this->assertAttributeSame($registry, 'registry', $builder);
    }
}