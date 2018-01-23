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
 * Rules for nested list and object validation.
 *
 * @copyright 2015-2018 LibreWorks contributors
 * @license   Apache-2.0
 */
class Nested implements \Caridea\Validate\Draft
{
    /**
     * @var string The operator type
     */
    private $operator;
    /**
     * @var \Caridea\Validate\Validator Optional. A validator for nested objects.
     */
    private $validator;
    /**
     * @var string Optional field on object that chooses rules.
     */
    private $field;

    /**
     * Creates a new NestedRule.
     *
     * @param string $operator The operator type
     * @param mixed|\Caridea\Validate\Validator The validator to use, or definitions to create one
     * @param string $field Optional field on object that chooses rules
     */
    protected function __construct(string $operator, $validator, string $field = null)
    {
        $this->operator = $operator;
        $this->validator = $validator;
        $this->field = $field;
    }

    /**
     * Finishes creating a rule using the parent builder.
     *
     * @param \Caridea\Validate\Builder $builder
     * @return \Caridea\Validate\Rule The fully created rule
     */
    public function finish(\Caridea\Validate\Builder $builder): \Caridea\Validate\Rule
    {
        if ($this->validator instanceof \Caridea\Validate\Validator) {
            return $this;
        } else {
            $rule = clone $this;
            switch ($this->operator) {
                case "nested_object":
                case "list_objects":
                    $rule->validator = $builder->build($this->validator);
                    return $rule;
                case "list":
                    $rule->validator = $builder->build((object)['entry' => $this->validator]);
                    return $rule;
                case "list_different_objects":
                    $validators = [];
                    foreach ($this->validator as $value => $ruleset) {
                        $validators[$value] = $builder->build($ruleset);
                    }
                    $rule->validator = new \Caridea\Validate\SwitchValidator($this->field, $validators);
                    return $rule;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function apply($value, $data = []): ?array
    {
        if (!($this->validator instanceof \Caridea\Validate\Validator)) {
            throw new \BadMethodCallException("This rule is a Draft. Try calling the 'finish' method to get the full Rule.");
        }
        if (!is_array($value) && !($value instanceof \Traversable)) {
            return ['FORMAT_ERROR'];
        }
        switch ($this->operator) {
            case "nested_object":
                $result = $this->validator->validate($value);
                return $result->hasErrors() ? $result->getErrors() : null;
            case "list":
                $errors = [];
                foreach ($value as $entry) {
                    $result = $this->validator->validate(['entry' => $entry]);
                    $errors[] = $result->hasErrors() ?
                        $result->getErrors()['entry'] : null;
                }
                return array_filter($errors) ? $errors : null;
            case "list_objects":
            case "list_different_objects":
                $errors = [];
                foreach ($value as $entry) {
                    try {
                        $result = $this->validator->validate($entry);
                        $errors[] = $result->hasErrors() ?
                            $result->getErrors() : null;
                    } catch (\InvalidArgumentException $e) {
                        $errors[] = 'FORMAT_ERROR';
                    }
                }
                return array_filter($errors) ? $errors : null;
        }
    }

    /**
     * Verifies an object value against separate validator rules.
     *
     * @param object $ruleset The validation ruleset
     * @return \Caridea\Validate\Rule\Nested the created rule
     */
    public static function nestedObject(\stdClass $ruleset): Nested
    {
        return new Nested("nested_object", $ruleset);
    }

    /**
     * Verifies each entry in a list using one or more rules.
     *
     * @param mixed $rules The rule or rules to enforce
     * @return \Caridea\Validate\Rule\Nested the created rule
     */
    public static function listOf($rules): Nested
    {
        return new Nested("list", $rules);
    }

    /**
     * Verifies each entry in a list against separate validator rules.
     *
     * @param object $ruleset The validation ruleset
     * @return \Caridea\Validate\Rule\Nested the created rule
     */
    public static function listOfObjects(\stdClass $ruleset): Nested
    {
        return new Nested("list_objects", $ruleset);
    }

    /**
     * Verifies each entry in a list using one of several validators based on a field value.
     *
     * @param string $field The deciding field name
     * @param object $rulesets The rulesets
     * @return \Caridea\Validate\Rule\Nested the created rule
     */
    public static function listOfDifferentObjects(string $field, \stdClass $rulesets): Nested
    {
        return new Nested('list_different_objects', $rulesets, $field);
    }
}
