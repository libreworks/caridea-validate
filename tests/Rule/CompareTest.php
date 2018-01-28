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
 * Generated by PHPUnit_SkeletonGenerator on 2015-07-21 at 12:58:00.
 */
class CompareTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Caridea\Validate\Rule\Compare::eq
     * @covers Caridea\Validate\Rule\Compare::__construct
     * @covers Caridea\Validate\Rule\Compare::apply
     */
    public function testEq()
    {
        $object = Compare::eq('ok');

        $format = [[], $object];
        foreach ($format as $v) {
            $this->assertEquals(['FORMAT_ERROR'], $object->apply($v));
        }
        $null = ['ok'];
        foreach ($null as $v) {
            $this->assertNull($object->apply($v));
        }
        $error = [0, false, 'bad', 1.1];
        foreach ($error as $v) {
            $this->assertEquals(['NOT_ALLOWED_VALUE'], $object->apply($v));
        }
    }

    /**
     * @covers Caridea\Validate\Rule\Compare::oneOf
     * @covers Caridea\Validate\Rule\Compare::__construct
     * @covers Caridea\Validate\Rule\Compare::apply
     */
    public function testOneOf()
    {
        $object = Compare::oneOf([1, 2, 'ok', 'yup']);

        $format = [[], $object];
        foreach ($format as $v) {
            $this->assertEquals(['FORMAT_ERROR'], $object->apply($v));
        }
        $null = [1, 2, 'ok', 'yup'];
        foreach ($null as $v) {
            $this->assertNull($object->apply($v));
        }
        $error = [0, false, 'bad', 1.1];
        foreach ($error as $v) {
            $this->assertEquals(['NOT_ALLOWED_VALUE'], $object->apply($v));
        }
    }

    /**
     * @covers Caridea\Validate\Rule\Compare::max
     * @covers Caridea\Validate\Rule\Compare::__construct
     * @covers Caridea\Validate\Rule\Compare::apply
     */
    public function testMax()
    {
        $object = Compare::max(50);

        $format = [[], $object];
        foreach ($format as $v) {
            $this->assertEquals(['FORMAT_ERROR'], $object->apply($v));
        }
        $null = [25, 0, -1, "42", "49.99", 50, 'no'];
        foreach ($null as $v) {
            $this->assertNull($object->apply($v));
        }
        $error = [100, '51', 50.1];
        foreach ($error as $v) {
            $this->assertEquals(['TOO_HIGH'], $object->apply($v));
        }
    }

    /**
     * @covers Caridea\Validate\Rule\Compare::min
     * @covers Caridea\Validate\Rule\Compare::__construct
     * @covers Caridea\Validate\Rule\Compare::apply
     */
    public function testMin()
    {
        $object = Compare::min(50);

        $format = [[], $object];
        foreach ($format as $v) {
            $this->assertEquals(['FORMAT_ERROR'], $object->apply($v));
        }
        $null = [100, '51', 50.1, 50];
        foreach ($null as $v) {
            $this->assertNull($object->apply($v));
        }
        $error = [25, 0, -1, "42", "49.99", "no"];
        foreach ($error as $v) {
            $this->assertEquals(['TOO_LOW'], $object->apply($v));
        }
    }

    /**
     * @covers Caridea\Validate\Rule\Compare::between
     * @covers Caridea\Validate\Rule\Compare::__construct
     * @covers Caridea\Validate\Rule\Compare::apply
     */
    public function testBetween()
    {
        $object = Compare::between(10, 20);

        $format = [[], $object];
        foreach ($format as $v) {
            $this->assertEquals(['FORMAT_ERROR'], $object->apply($v));
        }
        $null = [15, 10, 20, '12', 17.98];
        foreach ($null as $v) {
            $this->assertNull($object->apply($v));
        }
        $error = ['nope', 9, '9.99', 0, -1];
        foreach ($error as $v) {
            $this->assertEquals(['TOO_LOW'], $object->apply($v));
        }
        $error2 = [21, '42', 20.001];
        foreach ($error2 as $v) {
            $this->assertEquals(['TOO_HIGH'], $object->apply($v));
        }
    }

    /**
     * @covers Caridea\Validate\Rule\Compare::integer
     * @covers Caridea\Validate\Rule\Compare::__construct
     * @covers Caridea\Validate\Rule\Compare::apply
     */
    public function testInteger()
    {
        $object = Compare::integer();

        $format = [[], $object];
        foreach ($format as $v) {
            $this->assertEquals(['FORMAT_ERROR'], $object->apply($v));
        }
        $null = ['1', 0, -1, 12345, PHP_INT_MAX, '+123'];
        foreach ($null as $v) {
            $this->assertNull($object->apply($v));
        }
        $error = [1.1, '123.4', 'no', false, '123.no', '+123+', PHP_INT_MAX + 1];
        foreach ($error as $v) {
            $this->assertEquals(['NOT_INTEGER'], $object->apply($v));
        }
    }

    /**
     * @covers Caridea\Validate\Rule\Compare::positiveInteger
     * @covers Caridea\Validate\Rule\Compare::__construct
     * @covers Caridea\Validate\Rule\Compare::apply
     */
    public function testPositiveInteger()
    {
        $object = Compare::positiveInteger();

        $format = [[], $object];
        foreach ($format as $v) {
            $this->assertEquals(['FORMAT_ERROR'], $object->apply($v));
        }
        $null = ['123', 1, PHP_INT_MAX, 42];
        foreach ($null as $v) {
            $this->assertNull($object->apply($v));
        }
        $error = [123.234, 0, -1, "", 'no', false, PHP_INT_MAX + 1];
        foreach ($error as $v) {
            $this->assertEquals(['NOT_POSITIVE_INTEGER'], $object->apply($v));
        }
    }

    /**
     * @covers Caridea\Validate\Rule\Compare::decimal
     * @covers Caridea\Validate\Rule\Compare::__construct
     * @covers Caridea\Validate\Rule\Compare::apply
     */
    public function testDecimal()
    {
        $object = Compare::decimal();

        $format = [[], $object];
        foreach ($format as $v) {
            $this->assertEquals(['FORMAT_ERROR'], $object->apply($v));
        }
        $null = [123.234, PHP_INT_MAX + 1, -123.456, 0.0, "98.123123"];
        foreach ($null as $v) {
            $this->assertNull($object->apply($v));
        }
        $error = [1, 'nope', -123, '', false];
        foreach ($error as $v) {
            $this->assertEquals(['NOT_DECIMAL'], $object->apply($v));
        }
    }

    /**
     * @covers Caridea\Validate\Rule\Compare::positiveDecimal
     * @covers Caridea\Validate\Rule\Compare::__construct
     * @covers Caridea\Validate\Rule\Compare::apply
     */
    public function testPositiveDecimal()
    {
        $object = Compare::positiveDecimal();

        $format = [[], $object];
        foreach ($format as $v) {
            $this->assertEquals(['FORMAT_ERROR'], $object->apply($v));
        }
        $null = [123.234, PHP_INT_MAX + 1, 0.01, "123.123123"];
        foreach ($null as $v) {
            $this->assertNull($object->apply($v));
        }
        $error = [1, 'nope', -123, -123.456, false, ''];
        foreach ($error as $v) {
            $this->assertEquals(['NOT_POSITIVE_DECIMAL'], $object->apply($v), "'$v' passed");
        }
    }

    /**
     * @covers Caridea\Validate\Rule\Compare::equalToField
     * @covers Caridea\Validate\Rule\Compare::__construct
     * @covers Caridea\Validate\Rule\Compare::apply
     */
    public function testEqualToField()
    {
        $object = Compare::equalToField('password');

        $pw = 'correct horse battery staple';
        $data = ['password' => $pw];
        $this->assertNull($object->apply($pw, $data));
        $error = [1, 'nope', -123, '', null, false];
        foreach ($error as $v) {
            $this->assertEquals(['FIELDS_NOT_EQUAL'], $object->apply($v, $data));
        }
        $this->assertEquals(['FORMAT_ERROR'], $object->apply([], $data));
    }
}
