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
 * A validation result.
 *
 * @copyright 2015 LibreWorks contributors
 * @license   http://opensource.org/licenses/Apache-2.0 Apache 2.0 License
 */
class Result
{
    /**
     * @var boolean Whether the validation passed
     */
    private $passed;
    /**
     * @var array The result errors
     */
    private $errors;
    
    /**
     * Creates a new validation result.
     * 
     * @param array $errors Associative array of field name to error
     */
    public function __construct(array $errors)
    {
        $this->passed = empty($errors);
        $this->errors = $errors;
    }
    
    /**
     * Whether the validation passed.
     * 
     * @return boolean
     */
    public function hasErrors()
    {
        return !$this->passed;
    }
    
    /**
     * Gets all errors.
     * 
     * ```
     * [
     *     'name' => 'REQUIRED',
     *     'phone' => 'CANNOT_BE_EMPTY'
     * ]
     * ```
     * 
     * @return array Associative array of field name to error
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * Gets the errors as a JSON string.
     * 
     * @return string The errors as a JSON string
     */
    public function __toString()
    {
        return json_encode($this->errors);
    }
}
