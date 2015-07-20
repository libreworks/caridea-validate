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
 * Number related rules.
 *
 * @copyright 2015 LibreWorks contributors
 * @license   http://opensource.org/licenses/Apache-2.0 Apache 2.0 License
 */
class NumberRule implements Rule
{
    /**
     * @var string The operator type
     */
    private $operator;
    /**
     * @var mixed The optional comparison value
     */
    private $operand;
    
    /**
     * Creates a new NumberRule.
     * 
     * @param string $operator The operator type
     * @param int|float $operand The optional comparison value
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
        if (!is_int($value) && !is_float($value) && !is_string($value)) {
            return 'FORMAT_ERROR';
        }
        switch ($this->operator) {
            case "lt":
                return $value > $this->operand ? 'TOO_HIGH' : null;
            case "gt":
                return $value < $this->operand ? 'TOO_LOW' : null;
            case "bt":
                if ($value > max($this->operand)) {
                    return 'TOO_HIGH';
                } elseif ($value < min($this->operand)) {
                    return 'TOO_LOW';
                }
                return null;
            case "int":
                return is_int($value) || ctype_digit(trim($value, '-+')) ?
                    null : 'NOT_INTEGER';
            case "+int":
                return ((int) $value) <= 0 ? 'NOT_POSITIVE_INTEGER' : null;
            case "float":
                return is_float($value) || ($value === (string)(float)$value) ?
                    null : 'NOT_DECIMAL';
            case "+float":
                if (is_float($value) || is_int($value)) {
                    return $value <= 0 ? 'NOT_POSITIVE_DECIMAL' : null;
                } elseif ($value === (string)(float)$value) {
                    return ((float) $value) <= 0 ? 'NOT_POSITIVE_DECIMAL' : null;
                }
                return 'NOT_POSITIVE_DECIMAL';
        }
    }

    /**
     * Gets a rule that requires numbers to be no greater than a limit.
     * 
     * @param int|float $value The maximum value
     * @return \Caridea\Bind\Validate\NumberRule the created rule
     */
    public static function max($value)
    {
        return new NumberRule('lt', $value);
    }
    
    /**
     * Gets a rule that requires numbers to be no less than a limit.
     * 
     * @param int|float $value The minimum value
     * @return \Caridea\Bind\Validate\NumberRule the created rule
     */
    public static function min($value)
    {
        return new NumberRule('gt', $value);
    }
    
    /**
     * Gets a rule that requires numbers to be in a given range.
     * 
     * @param int|float $min The minimum value, inclusive
     * @param int|float $max The maximum value, inclusive
     * @return \Caridea\Bind\Validate\NumberRule the created rule
     */
    public static function between($min, $max)
    {
        return new NumberRule('bt', [$min, $max]);
    }
    
    /**
     * Gets a rule that matches integers and strings with integer values.
     * 
     * @return \Caridea\Bind\Validate\NumberRule the created rule
     */
    public static function integer()
    {
        return new NumberRule('int');
    }
    
    /**
     * Gets a rule that matches positive integers
     * 
     * @return \Caridea\Bind\Validate\NumberRule the created rule
     */
    public static function positiveInteger()
    {
        return new NumberRule('+int');
    }
    
    /**
     * Gets a rule that matches floats and strings with float values.
     * 
     * @return \Caridea\Bind\Validate\NumberRule the created rule
     */
    public static function decimal()
    {
        return new NumberRule('float');
    }
    
    /**
     * Gets a rule that matches positive floats
     * 
     * @return \Caridea\Bind\Validate\NumberRule the created rule
     */
    public static function positiveDecimal()
    {
        return new NumberRule('+float');
    }
}
