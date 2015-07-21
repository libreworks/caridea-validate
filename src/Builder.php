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
namespace Caridea\Validate;

/**
 * Builds validators.
 *
 * @copyright 2015 LibreWorks contributors
 * @license   http://opensource.org/licenses/Apache-2.0 Apache 2.0 License
 */
class Builder
{
    /**
     * @var array Associative array of definition name to function callback
     */
    private $definitions = [];
    
    /**
     * @var array Associative array of definition name to function callback
     */
    private static $defaultDefinitions = [
        'required'         => ['Caridea\Validate\Rule\Blank', 'required'],
        'not_empty'        => ['Caridea\Validate\Rule\Blank', 'notEmpty'],
        'not_empty_list'   => ['Caridea\Validate\Rule\Blank', 'notEmptyList'],
        'one_of'           => ['Caridea\Validate\Rule\Compare', 'oneOf'],
        'min_length'       => ['Caridea\Validate\Rule\Length', 'min'],
        'max_length'       => ['Caridea\Validate\Rule\Length', 'max'],
        'length_equal'     => ['Caridea\Validate\Rule\Length', 'equal'],
        'length_between'   => ['Caridea\Validate\Rule\Length', 'between'],
        'like'             => ['Caridea\Validate\Rule\Match', 'like'],
        'integer'          => ['Caridea\Validate\Rule\Compare', 'integer'],
        'positive_integer' => ['Caridea\Validate\Rule\Compare', 'positiveInteger'],
        'decimal'          => ['Caridea\Validate\Rule\Compare', 'decimal'],
        'positive_decimal' => ['Caridea\Validate\Rule\Compare', 'positiveDecimal'],
        'min_number'       => ['Caridea\Validate\Rule\Compare', 'min'],
        'max_number'       => ['Caridea\Validate\Rule\Compare', 'max'],
        'number_between'   => ['Caridea\Validate\Rule\Compare', 'between'],
        'email'            => ['Caridea\Validate\Rule\Match', 'email'],
        'iso_date'         => ['Caridea\Validate\Rule\Match', 'isoDate'],
        'url'              => ['Caridea\Validate\Rule\Match', 'url'],
        'nested_object'    => ['Caridea\Validate\Rule\Nested', 'nestedObject'],
        'list_of'          => ['Caridea\Validate\Rule\Nested', 'listOf'],
        'list_of_objects'  => ['Caridea\Validate\Rule\Nested', 'listOfObjects'],
        'list_of_different_objects' => ['Caridea\Validate\Rule\Nested', 'listOfDifferentObjects'],
    ];
    
    /**
     * Creates a new Validation Builder.
     */
    public function __construct()
    {
        $this->register(static::$defaultDefinitions);
    }
    
    /**
     * Registers rule definitions.
     * 
     * ```php
     * $builder = new \Caridea\Validate\Builder();
     * $builder->register([
     *     'adult' => ['My\Validate\AgeRule', 'adult'],
     *     'credit_card' => function(){return new CreditCardRule();},
     *     'something' => 'my_function_that_can_be_called'
     * ]);
     * ```
     * 
     * @param array $definitions Associative array of definition name to function callback
     * @return $this provides a fluent interface
     */
    public function register(array $definitions)
    {
        foreach ($definitions as $name => $callback) {
            $this->definitions[$name] = $callback;
        }
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
     * Currently, this function only supports JSON hashes as PHP objects, not
     * PHP associative arrays.
     * 
     * @param object $ruleset Object (as returned from `json_decode`) with ruleset
     * @return \Caridea\Validate\Validator the built validator
     */
    public function build($ruleset)
    {
        $validators = [];
        foreach ($ruleset as $field => $rules) {
            if (is_string($rules)) {
                $validators[$field] = $this->getRule($rules);
            } elseif (is_array($rules)) {
                $setup = [];
                foreach ($rules as $v) {
                    $setup = array_merge($setup, $this->getRule($v));
                }
                $validators[$field] = $setup;
            } elseif (is_object($rules)) {
                $validators[$field] = $this->getRule($rules);
            }
        }
        return new Validator($validators);
    }
    
    /**
     * Parses a rule definition.
     * 
     * @param string|object $rule Either a string name or an object with name → arguments
     * @param mixed $arg Optional constructor argument, or an arary of arguments
     * @return array An array of instantiated rules
     */
    protected function getRule($rule, $arg = null)
    {
        $rules = [];
        if (is_string($rule)) {
            if (isset($this->definitions[$rule])) {
                $vrule = is_array($arg) ?
                    call_user_func_array($this->definitions[$rule], $arg) :
                    call_user_func($this->definitions[$rule], $arg);
                if ($vrule instanceof Draft) {
                    $vrule = $vrule->finish($this);
                } elseif (!$vrule instanceof Rule) {
                    throw new \UnexpectedValueException('Definitions must return Rule objects');
                }
                $rules[] = $vrule;
            }
        } elseif (is_object($rule)) {
            foreach ($rule as $name => $args) {
                $rules = array_merge($rules, $this->getRule($name, $args));
            }
        }
        return $rules;
    }
}
