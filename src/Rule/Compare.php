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
 * Compares scalar values to some operand.
 *
 * @copyright 2015-2018 LibreWorks contributors
 * @license   Apache-2.0
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
    protected function __construct(string $operator, $operand = null)
    {
        $this->operator = $operator;
        $this->operand = $operand;
    }

    /**
     * {@inheritDoc}
     */
    public function apply($value, $data = []): ?array
    {
        if ("eqf" === $this->operator) {
            if ($value !== null && !is_scalar($value)) {
                return ['FORMAT_ERROR'];
            }
            return $value === $this->access($data, $this->operand) ?
                null : ['FIELDS_NOT_EQUAL'];
        }
        if (!is_scalar($value)) {
            return ['FORMAT_ERROR'];
        }
        switch ($this->operator) {
            case "in":
                return in_array($value, $this->operand, true) ? null : ['NOT_ALLOWED_VALUE'];
            case "lt":
                return $value > $this->operand ? ['TOO_HIGH'] : null;
            case "gt":
                return $value < $this->operand ? ['TOO_LOW'] : null;
            case "bt":
                if ($value > $this->operand[1]) {
                    return ['TOO_HIGH'];
                } elseif ($value < $this->operand[0]) {
                    return ['TOO_LOW'];
                }
                return null;
            case "int":
                return is_int($value) || ctype_digit(ltrim((string)$value, '-+')) ?
                    null : ['NOT_INTEGER'];
            case "+int":
                return (is_int($value) || ctype_digit(ltrim((string)$value, '-+'))) &&
                    ((int) $value) > 0 ? null : ['NOT_POSITIVE_INTEGER'];
            case "float":
                return is_float($value) || ($value === (string)(float)$value) ?
                    null : ['NOT_DECIMAL'];
            case "+float":
                if (is_float($value)) {
                    return $value <= 0 ? ['NOT_POSITIVE_DECIMAL'] : null;
                } elseif ($value === (string)(float)$value) {
                    return ((float) $value) <= 0 ? ['NOT_POSITIVE_DECIMAL'] : null;
                }
                return ['NOT_POSITIVE_DECIMAL'];
        }
    }

    /**
     * Gets a field from the values.
     *
     * This can be overridden to access by other means (e.g. object properties,
     * getter methods).
     *
     * @param mixed $values The values
     * @param string $field The field to access
     * @return mixed The accessed value
     */
    protected function access($values, string $field)
    {
        return isset($values[$field]) ? $values[$field] : null;
    }

    /**
     * Gets a rule that matches a value against another value.
     *
     * @param string $value The accepted value
     * @return \Caridea\Validate\Rule\Compare the created rule
     */
    public static function eq(string $value): Compare
    {
        return new Compare('in', [$value]);
    }

    /**
     * Gets a rule that matches a value against a list of accepted values.
     *
     * @param array $values The accepted values
     * @return \Caridea\Validate\Rule\Compare the created rule
     */
    public static function oneOf(array $values): Compare
    {
        return new Compare('in', $values);
    }

    /**
     * Gets a rule that requires numbers to be no greater than a limit.
     *
     * @param int|float $value The maximum value
     * @return \Caridea\Validate\Rule\Compare the created rule
     */
    public static function max($value): Compare
    {
        return new Compare('lt', $value);
    }

    /**
     * Gets a rule that requires numbers to be no less than a limit.
     *
     * @param int|float $value The minimum value
     * @return \Caridea\Validate\Rule\Compare the created rule
     */
    public static function min($value): Compare
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
    public static function between($min, $max): Compare
    {
        $value = $min > $max ? [$max, $min] : [$min, $max];
        return new Compare('bt', $value);
    }

    /**
     * Gets a rule that matches integers and strings with integer values.
     *
     * @return \Caridea\Validate\Rule\Compare the created rule
     */
    public static function integer(): Compare
    {
        return new Compare('int');
    }

    /**
     * Gets a rule that matches positive integers
     *
     * @return \Caridea\Validate\Rule\Compare the created rule
     */
    public static function positiveInteger(): Compare
    {
        return new Compare('+int');
    }

    /**
     * Gets a rule that matches floats and strings with float values.
     *
     * @return \Caridea\Validate\Rule\Compare the created rule
     */
    public static function decimal(): Compare
    {
        return new Compare('float');
    }

    /**
     * Gets a rule that matches positive floats
     *
     * @return \Caridea\Validate\Rule\Compare the created rule
     */
    public static function positiveDecimal(): Compare
    {
        return new Compare('+float');
    }

    /**
     * Gets a rule that compares two fields for equality
     *
     * @param string $field The other field whose value will be compared
     * @return \Caridea\Validate\Rule\Compare the created rule
     */
    public static function equalToField(string $field): Compare
    {
        return new Compare('eqf', $field);
    }
}
