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
 * Parses rule definitions
 *
 * @since 3.0.0
 */
class Parser
{
    /**
     * @var \Caridea\Validate\Registry $registry
     */
    private $registry;

    /**
     * Creates a new Validation rule registry.
     *
     * @param \Caridea\Validate\Registry $registry The registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Tests if an array is associative.
     *
     * @param array $array The array to test
     * @return bool Whether the array is associative
     */
    public function isAssociative(array $array): bool
    {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    /**
     * Turns a number of rule definitions into an actual Rule Set.
     *
     * @param string|object|array $rules The rules to parse
     * @return \Caridea\Validate\Rule\Set The set of Rules
     */
    public function parse($rules): Rule\Set
    {
        $isArray = is_array($rules);
        $isAssoc = $isArray && $this->isAssociative($rules);
        $set = null;
        if (is_string($rules) || is_object($rules) || $isAssoc) {
            $set = $this->getRule($rules);
        } elseif ($isArray) {
            foreach ($rules as $v) {
                $toAdd = $this->getRule($v);
                $set = $set === null ? $toAdd : $set->merge($toAdd);
            }
        }
        return $set ?? new Rule\Set();
    }

    /**
     * Parses rule definitions.
     *
     * @param string|object|array $rule Either a string name, an associative
     *        array, or an object with name → arguments
     * @param mixed $arg Optional constructor argument, or an array of arguments
     * @return \Caridea\Validate\Rule\Set An set of instantiated rules
     */
    public function getRule($rule, $arg = null): Rule\Set
    {
        $rules = new Rule\Set();
        if (is_string($rule)) {
            $vrule = $this->registry->factory($rule, $arg);
            if ($vrule instanceof Draft) {
                $vrule = $vrule->finish($this->registry);
            }
            $rules->add($vrule);
        } elseif (is_object($rule) || is_array($rule)) {
            foreach ($rule as $name => $args) {
                $rules->merge($this->getRule($name, $args));
            }
        }
        return $rules;
    }
}
