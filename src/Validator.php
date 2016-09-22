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
 * @copyright 2015-2016 LibreWorks contributors
 * @license   http://opensource.org/licenses/Apache-2.0 Apache 2.0 License
 */
namespace Caridea\Validate;

/**
 * An immutable set of rules for a set of fields that can validate data.
 *
 * @copyright 2015-2016 LibreWorks contributors
 * @license   http://opensource.org/licenses/Apache-2.0 Apache 2.0 License
 */
class Validator
{
    /**
     * @var array<string,array<Rule>> Associative array of field name to list of rules
     */
    protected $ruleset;

    /**
     * Creates a new validator.
     *
     * @param array<string,array<Rule>> $ruleset Associative array of field name to list of rules
     */
    public function __construct(array $ruleset)
    {
        $this->ruleset = $ruleset;
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
     * Iterates over the ruleset and collects any error codes.
     *
     * @param object|array $values An object or associative array to validate
     * @return array Associative array of field name to error
     * @throws \InvalidArgumentException if `$values` is null
     */
    protected function iterate($values)
    {
        if (!is_object($values) && !is_array($values)) {
            throw new \InvalidArgumentException("Unable to validate provided object");
        }
        $errors = [];
        foreach ($this->ruleset as $field => $rules) {
            $value = $this->access($values, $field);
            $empty = $value === null || $value === '';
            foreach ($rules as $rule) {
                $error = (!$empty || $rule instanceof Rule\Blank) ?
                    $rule->apply($value, $values) : null;
                if (is_array($error) && count($error) > 0) {
                    $errors[$field] = count($error) > 1 ? $error : current($error);
                    break;
                } elseif ($error !== null) {
                    $errors[$field] = $error;
                    break;
                }
            }
        }
        return $errors;
    }

    /**
     * Validates the provided value, returning a result object.
     *
     * @param object|array $values An object or associative array to validate
     * @return \Caridea\Validate\Result The validation results
     * @throws \InvalidArgumentException if `$values` is null
     */
    public function validate($values): Result
    {
        return new Result($this->iterate($values));
    }

    /**
     * Validates the provided value, throwing an exception upon failure.
     *
     * @param object|array $values An object or associative array to validate
     * @throws \Caridea\Validate\Exception\Invalid if validation fails
     * @throws \InvalidArgumentException if `$values` is null
     */
    public function assert($values)
    {
        $errors = $this->iterate($values);
        if (!empty($errors)) {
            throw new Exception\Invalid($errors);
        }
    }
}
