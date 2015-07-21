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
 * @copyright 2015 LibreWorks contributors
 * @license   http://opensource.org/licenses/Apache-2.0 Apache 2.0 License
 */
namespace Caridea\Validate\Rule;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-07-21 at 13:53:52.
 */
class LengthTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Caridea\Validate\Rule\Length::max
     * @covers Caridea\Validate\Rule\Length::__construct
     * @covers Caridea\Validate\Rule\Length::apply
     */
    public function testMax()
    {
        $object = Length::max(4);
        
        $format = [[], $object, 1, false, 0.4];
        foreach ($format as $v) {
            $this->assertEquals('FORMAT_ERROR', $object->apply($v));
        }
        $null = ['aoeu', '123', '', ' ', 'h', '私', 'おはよう'];
        foreach ($null as $v) {
            $this->assertNull($object->apply($v));
        }
        $error = ['12345', '努力と根性'];
        foreach ($error as $v) {
            $this->assertEquals('TOO_LONG', $object->apply($v));
        }
    }

    /**
     * @covers Caridea\Validate\Rule\Length::min
     * @covers Caridea\Validate\Rule\Length::__construct
     * @covers Caridea\Validate\Rule\Length::apply
     */
    public function testMin()
    {
        $object = Length::min(5);
        
        $format = [[], $object, 1, false, 0.4];
        foreach ($format as $v) {
            $this->assertEquals('FORMAT_ERROR', $object->apply($v));
        }
        $null = ['12345', '努力と根性', 'excellent'];
        foreach ($null as $v) {
            $this->assertNull($object->apply($v));
        }
        $error = ['aoeu', '123', '', ' ', 'h', '私', 'おはよう'];
        foreach ($error as $v) {
            $this->assertEquals('TOO_SHORT', $object->apply($v));
        }
    }

    /**
     * @covers Caridea\Validate\Rule\Length::equal
     * @covers Caridea\Validate\Rule\Length::__construct
     * @covers Caridea\Validate\Rule\Length::apply
     */
    public function testEqual()
    {
        $object = Length::equal(5);
        
        $format = [[], $object, 1, false, 0.4];
        foreach ($format as $v) {
            $this->assertEquals('FORMAT_ERROR', $object->apply($v));
        }
        $null = ['12345', '努力と根性'];
        foreach ($null as $v) {
            $this->assertNull($object->apply($v));
        }
        $error = ['aoeu', '123', '', ' ', 'h', '私', 'おはよう'];
        foreach ($error as $v) {
            $this->assertEquals('TOO_SHORT', $object->apply($v));
        }
        $error = ['excellent', 'way too long'];
        foreach ($error as $v) {
            $this->assertEquals('TOO_LONG', $object->apply($v));
        }
    }

    /**
     * @covers Caridea\Validate\Rule\Length::between
     * @covers Caridea\Validate\Rule\Length::__construct
     * @covers Caridea\Validate\Rule\Length::apply
     */
    public function testBetween()
    {
        $object = Length::between(5, 2);
        
        $format = [[], $object, 1, false, 0.4];
        foreach ($format as $v) {
            $this->assertEquals('FORMAT_ERROR', $object->apply($v));
        }
        $null = ['hi', '123', 'aoeu', '12345', 'おはよう', '努力と根性'];
        foreach ($null as $v) {
            $this->assertNull($object->apply($v));
        }
        $error = ['', ' ', 'h', '私'];
        foreach ($error as $v) {
            $this->assertEquals('TOO_SHORT', $object->apply($v));
        }
        $error = ['excellent', 'way too long'];
        foreach ($error as $v) {
            $this->assertEquals('TOO_LONG', $object->apply($v));
        }
    }
}
