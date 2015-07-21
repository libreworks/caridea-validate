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
namespace Caridea\Validate\Exception;

/**
 * Validation Exception
 *
 * @copyright 2015 LibreWorks contributors
 * @license   http://opensource.org/licenses/Apache-2.0 Apache 2.0 License
 */
class Invalid extends \UnexpectedValueException implements \Caridea\Bind\Exception
{
    /**
     * @var array $errors Associative array of field name to error
     */
    private $errors;

    /**
     * Creates a new Validation exception.
     * 
     * @param array $errors Associative array of field name to error
     */
    public function __construct(array $errors)
    {
        parent::__construct("Validation failed: " . json_encode($errors));
        $this->errors = $errors;
    }
    
    /**
     * Gets the failed validation errors.
     * 
     * @return array Associative array of field name to error
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
