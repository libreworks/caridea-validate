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
 * A container for validation rules.
 */
class Registry
{
    /**
     * @var array<string,callable> Associative array of definition name to function callback
     */
    private $definitions = [];

    /**
     * @var array<string,callable> Associative array of definition name to function callback
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
        'timezone'         => ['Caridea\Validate\Rule\Timezone', 'timezone'],
        'equal_to_field'   => ['Caridea\Validate\Rule\Compare', 'equalToField'],
        'nested_object'    => ['Caridea\Validate\Rule\Nested', 'nestedObject'],
        'list_of'          => ['Caridea\Validate\Rule\Nested', 'listOf'],
        'list_of_objects'  => ['Caridea\Validate\Rule\Nested', 'listOfObjects'],
        'list_of_different_objects' => ['Caridea\Validate\Rule\Nested', 'listOfDifferentObjects'],
    ];

    /**
     * Creates a new Validation rule registry.
     */
    public function __construct()
    {
        $this->definitions = array_merge([], self::$defaultDefinitions);
    }

    /**
     * Registers rule definitions.
     *
     * ```php
     * $registry = new \Caridea\Validate\Registry();
     * $registry->register([
     *     'adult' => ['My\Validate\AgeRule', 'adult'],
     *     'credit_card' => function(){return new CreditCardRule();},
     *     'something' => 'my_function_that_can_be_called'
     * ]);
     * ```
     *
     * @param array<string,callable> $definitions Associative array of definition name to function callback
     * @return $this provides a fluent interface
     */
    public function register(array $definitions): self
    {
        foreach ($definitions as $name => $callback) {
            if (!is_callable($callback)) {
                throw new \InvalidArgumentException('Values passed to register must be callable');
            }
            $this->definitions[$name] = $callback;
        }
        return $this;
    }

    /**
     * Constructs a validation rule.
     *
     * @param string $name A string name
     * @param mixed $arg Optional constructor argument, or an array of arguments
     * @return \Caridea\Validate\Rule The instantiated rule
     * @throws \InvalidArgumentException if the rule name is not registered
     * @throws \UnexpectedValueException if the factory returns a non-Rule
     */
    public function factory(string $name, $arg = null): Rule
    {
        if (!array_key_exists($name, $this->definitions)) {
            throw new \InvalidArgumentException("No rule registered with name: $name");
        }
        $vrule = is_array($arg) ?
            call_user_func_array($this->definitions[$name], $arg) :
            call_user_func($this->definitions[$name], $arg);
        if (!$vrule instanceof Rule) {
            throw new \UnexpectedValueException('Definitions must return Rule objects');
        }
        return $vrule;
    }

    /**
     * Creates a new Builder using this Repository.
     *
     * @return \Caridea\Validate\Builder The builder
     */
    public function builder(): Builder
    {
        return new Builder($this);
    }
}
