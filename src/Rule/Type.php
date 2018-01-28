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

use Caridea\Validate\Parser;

/**
 * Compares values to some type
 *
 * @copyright 2015-2018 LibreWorks contributors
 * @license   Apache-2.0
 */
class Type implements \Caridea\Validate\Rule
{
    /**
     * @var string The operator type
     */
    private $operator;

    /**
     * Creates a new CompareRule.
     *
     * @param string $operator The operator type
     */
    protected function __construct(string $operator)
    {
        $this->operator = $operator;
    }

    /**
     * {@inheritDoc}
     */
    public function apply($value, $data = []): ?array
    {
        switch ($this->operator) {
            case "string":
                return is_scalar($value) ? null : ['FORMAT_ERROR'];
            case "object":
                return is_object($value) || (is_array($value) && Parser::isAssociative($value)) ?
                    null : ['FORMAT_ERROR'];
        }
    }

    /**
     * Gets a rule that matches a value against another value.
     *
     * @return \Caridea\Validate\Rule\Type the created rule
     */
    public static function string(): Type
    {
        return new Type('string');
    }

    /**
     * Gets a rule that matches a value against a list of accepted values.
     *
     * @return \Caridea\Validate\Rule\Compare the created rule
     */
    public static function anyObject(): Type
    {
        return new Type('object');
    }
}
