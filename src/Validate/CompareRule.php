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
namespace Caridea\Bind\Validate;

/**
 * Compares scalar values to some operand.
 *
 * @copyright 2015 LibreWorks contributors
 * @license   http://opensource.org/licenses/Apache-2.0 Apache 2.0 License
 */
class CompareRule implements Rule
{
    /**
     * @var string The operator type
     */
    private $operator;
    /**
     * @var mixed The comparison value
     */
    private $operand;

    /**
     * Creates a new CompareRule.
     * 
     * @param string $operator The operator type
     * @param mixed $operand Optional comparison value
     */
    protected function __construct($operator, $operand = null)
    {
        $this->operator = $operator;
        $this->operand = $operand;
    }
    
    /**
     * Validates the provided value.
     * 
     * @param mixed $value A value to validate against the rule
     * @return string An error code, or null if validation succeeded
     */
    public function apply($value)
    {
        if (!is_scalar($value)) {
            return 'FORMAT_ERROR';
        }
        switch ($this->operator) {
            case "in":
                return in_array($value, $this->operand) ? null : 'NOT_ALLOWED_VALUE';
        }
    }

    /**
     * Gets a rule that matches a value against a list of accepted values.
     * 
     * @param array $values The accepted values
     * @return \Caridea\Bind\Validate\CompareRule the created rule
     */
    public static function oneOf(array $values)
    {
        return new CompareRule('in', $values);
    }
}
