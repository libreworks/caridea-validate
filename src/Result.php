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
 * A validation result.
 *
 * @copyright 2015-2018 LibreWorks contributors
 * @license   Apache-2.0
 */
class Result
{
    /**
     * @var bool Whether the validation passed
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
     * @return bool
     */
    public function hasErrors(): bool
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
    public function getErrors(): array
    {
        return $this->errors;
    }
    
    /**
     * Gets the errors as a JSON string.
     *
     * @return string The errors as a JSON string
     */
    public function __toString(): string
    {
        return json_encode($this->errors);
    }
}
