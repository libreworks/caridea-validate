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
 * Chooses between validators based on object field value.
 *
 * @copyright 2015 LibreWorks contributors
 * @license   http://opensource.org/licenses/Apache-2.0 Apache 2.0 License
 */
class SwitchValidator extends Validator
{
    /**
     * @var string Associative array of field name to list of rules
     */
    protected $field;
    /**
     * @var Validator[] Associative array of field value to validator
     */
    protected $validators;
    
    /**
     * Creates a new switch validator.
     * 
     * @param array $ruleset Associative array of field name to list of rules
     */
    public function __construct($field, array $validators)
    {
        parent::__construct([]);
        $this->field = $field;
        $this->validators = $validators;
    }
    
    /**
     * Iterates over the ruleset and collects any error codes.
     * 
     * @param object|array $values An object or associative array to validate
     * @return array Associative array of field name to error
     * @throws \InvalidArgumentException if `$values` is null or matching validator
     */
    protected function iterate($values)
    {
        if (!is_object($values) && !is_array($values)) {
            throw new \InvalidArgumentException("Unable to validate provided object");
        }
        $value = $this->access($values, $this->field);
        if (!isset($this->validators[$value])) {
            throw new \InvalidArgumentException("No validator found for [{$this->field}] of '$value'");
        } else {
            return $this->validators[$value]->validate($values)->getErrors();
        }
    }
}
