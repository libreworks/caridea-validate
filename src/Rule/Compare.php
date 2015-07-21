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
 * Compares scalar values to some operand.
 *
 * @copyright 2015 LibreWorks contributors
 * @license   http://opensource.org/licenses/Apache-2.0 Apache 2.0 License
 */
class Compare implements \Caridea\Validate\Rule
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
                return in_array($value, $this->operand, true) ? null : 'NOT_ALLOWED_VALUE';
            case "lt":
                return $value > $this->operand ? 'TOO_HIGH' : null;
            case "gt":
                return $value < $this->operand ? 'TOO_LOW' : null;
            case "bt":
                if ($value > $this->operand[1]) {
                    return 'TOO_HIGH';
                } elseif ($value < $this->operand[0]) {
                    return 'TOO_LOW';
                }
                return null;
            case "int":
                return is_int($value) || ctype_digit(ltrim($value, '-+')) ?
                    null : 'NOT_INTEGER';
            case "+int":
                return (is_int($value) || ctype_digit(ltrim($value, '-+'))) &&
                    ((int) $value) > 0 ? null : 'NOT_POSITIVE_INTEGER';
            case "float":
                return is_float($value) || ($value === (string)(float)$value) ?
                    null : 'NOT_DECIMAL';
            case "+float":
                if (is_float($value)) {
                    return $value <= 0 ? 'NOT_POSITIVE_DECIMAL' : null;
                } elseif ($value === (string)(float)$value) {
                    return ((float) $value) <= 0 ? 'NOT_POSITIVE_DECIMAL' : null;
                }
                return 'NOT_POSITIVE_DECIMAL';
        }
    }

    /**
     * Gets a rule that matches a value against a list of accepted values.
     *
     * @param array $values The accepted values
     * @return \Caridea\Validate\Rule\Compare the created rule
     */
    public static function oneOf(array $values)
    {
        return new Compare('in', $values);
    }

    /**
     * Gets a rule that requires numbers to be no greater than a limit.
     *
     * @param int|float $value The maximum value
     * @return \Caridea\Validate\Rule\Compare the created rule
     */
    public static function max($value)
    {
        return new Compare('lt', $value);
    }
    
    /**
     * Gets a rule that requires numbers to be no less than a limit.
     *
     * @param int|float $value The minimum value
     * @return \Caridea\Validate\Rule\Compare the created rule
     */
    public static function min($value)
    {
        return new Compare('gt', $value);
    }
    
    /**
     * Gets a rule that requires numbers to be in a given range.
     *
     * @param int|float $min The minimum value, inclusive
     * @param int|float $max The maximum value, inclusive
     * @return \Caridea\Validate\Rule\Compare the created rule
     */
    public static function between($min, $max)
    {
        $value = $min > $max ? [$max, $min] : [$min, $max];
        return new Compare('bt', $value);
    }
    
    /**
     * Gets a rule that matches integers and strings with integer values.
     *
     * @return \Caridea\Validate\Rule\Compare the created rule
     */
    public static function integer()
    {
        return new Compare('int');
    }
    
    /**
     * Gets a rule that matches positive integers
     *
     * @return \Caridea\Validate\Rule\Compare the created rule
     */
    public static function positiveInteger()
    {
        return new Compare('+int');
    }
    
    /**
     * Gets a rule that matches floats and strings with float values.
     *
     * @return \Caridea\Validate\Rule\Compare the created rule
     */
    public static function decimal()
    {
        return new Compare('float');
    }
    
    /**
     * Gets a rule that matches positive floats
     *
     * @return \Caridea\Validate\Rule\Compare the created rule
     */
    public static function positiveDecimal()
    {
        return new Compare('+float');
    }
}
