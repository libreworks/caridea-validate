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
 * @copyright 2015-2018 LibreWorks contributors
 * @license   Apache-2.0
 */
namespace Caridea\Validate;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-07-21 at 14:22:29.
 */
class ResultTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Caridea\Validate\Result::__construct
     * @covers Caridea\Validate\Result::hasErrors
     */
    public function testHasErrors()
    {
        $object = new Result([]);
        $this->assertFalse($object->hasErrors());
        $object = new Result(['error' => 'ERROR']);
        $this->assertTrue($object->hasErrors());
    }

    /**
     * @covers Caridea\Validate\Result::getErrors
     */
    public function testGetErrors()
    {
        $errors = ['foo' => 'BAR'];
        $object = new Result($errors);
        $this->assertSame($errors, $object->getErrors());
    }

    /**
     * @covers Caridea\Validate\Result::__toString
     */
    public function testtoString()
    {
        $errors = ['foo' => 'BAR'];
        $object = new Result($errors);
        $this->assertSame(json_encode($errors), (string)$object);
    }
}
