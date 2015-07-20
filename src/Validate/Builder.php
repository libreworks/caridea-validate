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
        'required'         => ['Caridea\Bind\Validate\EmptyRule', 'required'],
        'not_empty'        => ['Caridea\Bind\Validate\EmptyRule', 'notEmpty'],
        'not_empty_list'   => ['Caridea\Bind\Validate\EmptyRule', 'notEmptyList'],
        'one_of'           => ['Caridea\Bind\Validate\CompareRule', 'oneOf'],
        'min_length'       => ['Caridea\Bind\Validate\LengthRule', 'min'],
        'max_length'       => ['Caridea\Bind\Validate\LengthRule', 'max'],
        'length_equal'     => ['Caridea\Bind\Validate\LengthRule', 'equal'],
        'length_between'   => ['Caridea\Bind\Validate\LengthRule', 'between'],
        'like'             => ['Caridea\Bind\Validate\CompareRule', 'matches'],
        'integer'          => ['Caridea\Bind\Validate\NumberRule', 'integer'],
        'positive_integer' => ['Caridea\Bind\Validate\NumberRule', 'positiveInteger'],
        'decimal'          => ['Caridea\Bind\Validate\NumberRule', 'decimal'],
        'positive_decimal' => ['Caridea\Bind\Validate\NumberRule', 'positiveDecimal'],
        'min_number'       => ['Caridea\Bind\Validate\NumberRule', 'min'],
        'max_number'       => ['Caridea\Bind\Validate\NumberRule', 'max'],
        'number_between'   => ['Caridea\Bind\Validate\NumberRule', 'between'],
        'email'            => ['Caridea\Bind\Validate\CompareRule', 'email'],
        'iso_date'         => ['Caridea\Bind\Validate\CompareRule', 'isoDate'],
        'url'              => ['Caridea\Bind\Validate\CompareRule', 'url'],
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
     * $builder = new \Caridea\Bind\Validate\Builder();
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
     *     gender: { one_of: ['male', 'female'] },
     *     phone: {max_length: 10},
     *     password: ['required', {min_length: 10} ]
     *     password2: { equal_to_field: 'password' }
     * }
     * ```
     * ```php
     * $ruleset = json_decode(file_get_contents('rules.json'));
     * $builder = new \Caridea\Bind\Validate\Builder();
     * $validator = $builder->build($ruleset);
     * ```
     * Currently, this function only supports JSON hashes as PHP objects, not
     * PHP associative arrays.
     * 
     * @param object $ruleset Object (as returned from `json_decode`) with ruleset
     * @return \Caridea\Bind\Validate\Validator the built validator
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
                $rules[] = is_array($arg) ?
                    call_user_func_array($this->definitions[$rule], $arg) :
                    call_user_func($this->definitions[$rule], $arg);
            }
        } elseif (is_object($rule)) {
            $rules = [];
            foreach ($rule as $name => $args) {
                $rules = array_merge($rules, $this->getRule($name, $args));
            }
            return $rules;
        }
        return $rules;
    }
}