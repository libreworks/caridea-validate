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
namespace Caridea\Validate\Rule;

/**
 * Rules for empty values
 *
 * @copyright 2015-2016 LibreWorks contributors
 * @license   http://opensource.org/licenses/Apache-2.0 Apache 2.0 License
 */
class Blank implements \Caridea\Validate\Rule
{
    /**
     * @var string The operator type
     */
    private $operator;
    
    /**
     * Creates a new EmptyRule.
     *
     * @param string $operator The operator type
     */
    protected function __construct($operator)
    {
        $this->operator = $operator;
    }
    
    /**
     * Validates the provided value.
     *
     * @param mixed $value A value to validate against the rule
     * @param array|object $data The dataset which contains this field
     * @return array|string An array of error codes, a single error code, or
     *     null if validation succeeded
     */
    public function apply($value, $data = [])
    {
        switch ($this->operator) {
            case "required":
                return $value === null || $value === '' ? 'REQUIRED' : null;
            case "empty":
                return $value === '' ? 'CANNOT_BE_EMPTY' : null;
            case "list":
                if (empty($value)) {
                    return 'CANNOT_BE_EMPTY';
                } elseif (!is_array($value) && !($value instanceof \Countable)) {
                    return 'FORMAT_ERROR';
                }
                return count($value) === 0 ? 'CANNOT_BE_EMPTY' : null;
        }
    }
    
    /**
     * Gets a rule that requires values to be non-null and not empty string.
     *
     * @return \Caridea\Validate\Rule\Blank the created rule
     */
    public static function required()
    {
        return new Blank('required');
    }
    
    /**
     * Gets a rule that requires strings to be non-empty.
     *
     * @return \Caridea\Validate\Rule\Blank
     */
    public static function notEmpty()
    {
        return new Blank('empty');
    }
    
    /**
     * Gets a rule that requires an array or `Countable` to be non-empty.
     *
     * @return \Caridea\Validate\Rule\Blank
     */
    public static function notEmptyList()
    {
        return new Blank('list');
    }
}
