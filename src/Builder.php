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
namespace Caridea\Validate;

/**
 * Builds validators.
 *
 * @copyright 2015-2018 LibreWorks contributors
 * @license   Apache-2.0
 */
class Builder
{
    /**
     * @var \Caridea\Validate\Registry
     */
    private $registry;
    /**
     * @var array<string,array<Rule>>
     */
    private $validators = [];

    /**
     * Creates a new Validation Builder.
     *
     * @param \Caridea\Validate\Registry $registry The registry.
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Adds one or more rules to this builder.
     *
     * @param string $field The field to validate
     * @param string|object|array $rules Either a string name, an associative
     *        array, or an object with name → arguments
     * @return $this provides a fluent interface
     */
    public function field(string $field, ...$rules): self
    {
        $vrules = [];
        foreach ($rules as $rule) {
            $vrules = array_merge($vrules, $this->getRule($rule));
        }
        $this->validators[$field] = $vrules;
        return $this;
    }

    /**
     * Builds a validator for the provided ruleset.
     *
     * ```javascript
     * // rules.json
     * {
     *     name: 'required',
     *     email: ['required', 'email'],
     *     drinks: { one_of: [['coffee', 'tea']] },
     *     phone: {max_length: 10}
     * }
     * ```
     * ```php
     * $ruleset = json_decode(file_get_contents('rules.json'));
     * $builder = new \Caridea\Validate\Builder();
     * $validator = $builder->build($ruleset);
     * ```
     *
     * @param object|array $ruleset Object or associative array (as returned
     *        from `json_decode`) with ruleset, or `null` to use defined rules.
     * @return \Caridea\Validate\Validator the built validator
     */
    public function build($ruleset = null): Validator
    {
        $validators = array_merge([], $this->validators);
        if (is_object($ruleset) || (is_array($ruleset) && count(array_filter(array_keys($ruleset), 'is_string')) > 0)) {
            foreach ($ruleset as $field => $rules) {
                $isArray = is_array($rules);
                $isAssoc = $isArray &&
                    count(array_filter(array_keys($rules), 'is_string')) > 0;
                if (is_string($rules) || is_object($rules) || $isAssoc) {
                    $validators[$field] = $this->getRule($rules);
                } elseif ($isArray) {
                    $setup = [];
                    foreach ($rules as $v) {
                        $setup = array_merge($setup, $this->getRule($v));
                    }
                    $validators[$field] = $setup;
                }
            }
        }
        return new Validator($validators);
    }

    /**
     * Parses rule definitions.
     *
     * @param string|object|array $rule Either a string name, an associative
     *        array, or an object with name → arguments
     * @param mixed $arg Optional constructor argument, or an array of arguments
     * @return array<Rule> An array of instantiated rules
     */
    protected function getRule($rule, $arg = null): array
    {
        $rules = [];
        if (is_string($rule)) {
            $vrule = $this->registry->factory($rule, $arg);
            if ($vrule instanceof Draft) {
                $vrule = $vrule->finish($this);
            }
            $rules[] = $vrule;
        } elseif (is_object($rule) || is_array($rule)) {
            foreach ($rule as $name => $args) {
                $rules = array_merge($rules, $this->getRule($name, $args));
            }
        }
        return $rules;
    }
}
