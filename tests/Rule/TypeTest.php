<?php
declare(strict_types=1);
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
 * @copyright 2015-2018 LibreWorks contributors
 * @license   Apache-2.0
 */
namespace Caridea\Validate\Rule;

/**
 * Generated by hand.
 */
class TypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Caridea\Validate\Rule\Type::string
     * @covers Caridea\Validate\Rule\Type::__construct
     * @covers Caridea\Validate\Rule\Type::apply
     */
    public function testString()
    {
        $object = Type::string();

        $format = [null, [1, 2, 3], ['foo' => 'bar'], $object];
        foreach ($format as $v) {
            $this->assertEquals(['FORMAT_ERROR'], $object->apply($v));
        }
        $null = [false, 123, 123.234, PHP_INT_MAX + 1, 0.01, "123.123123"];
        foreach ($null as $v) {
            $this->assertNull($object->apply($v));
        }
    }

    /**
     * @covers Caridea\Validate\Rule\Type::anyObject
     * @covers Caridea\Validate\Rule\Type::__construct
     * @covers Caridea\Validate\Rule\Type::apply
     */
    public function testAnyObject()
    {
        $object = Type::anyObject();

        $this->assertNull($object->apply($object));
        $this->assertNull($object->apply(['foo' => 'bar']));

        $error = [1, 'nope', -123, '', null, false, [1,2,3]];
        foreach ($error as $v) {
            $this->assertEquals(['FORMAT_ERROR'], $object->apply($v));
        }
    }
}
