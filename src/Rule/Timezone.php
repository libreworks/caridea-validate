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
 * @copyright 2015-2016 LibreWorks contributors
 * @license   http://opensource.org/licenses/Apache-2.0 Apache 2.0 License
 */
namespace Caridea\Validate\Rule;

/**
 * Tests strings as valid timezone identifier.
 *
 * @copyright 2015-2016 LibreWorks contributors
 * @license   http://opensource.org/licenses/Apache-2.0 Apache 2.0 License
 */
class Timezone implements \Caridea\Validate\Rule
{
    /**
     * @var array The comparison value
     */
    private $zones;

    /**
     * Creates a new Timezone Rule.
     */
    protected function __construct()
    {
        $this->zones = timezone_identifiers_list(\DateTimeZone::ALL_WITH_BC);
    }
    
    /**
     * Validates the provided value.
     *
     * @param mixed $value A value to validate against the rule
     * @param array|object $data The dataset which contains this field
     * @return array|string An array of error codes, a single error code, or
     *     null if validation succeeded
     */
    public function apply($value, $data = [])
    {
        if (!is_string($value)) {
            return 'FORMAT_ERROR';
        }
        return in_array($value, $this->zones, true) ? null : 'WRONG_TIMEZONE';
    }

    /**
     * Gets a rule that tests a string as a valid timezone.
     *
     * @return \Caridea\Validate\Rule\Timezone the created rule
     */
    public static function timezone()
    {
        return new Timezone();
    }
}
