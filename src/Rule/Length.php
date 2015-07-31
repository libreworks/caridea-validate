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
namespace Caridea\Validate\Rule;

/**
 * Compares string length to accepted boundaries.
 *
 * @copyright 2015 LibreWorks contributors
 * @license   http://opensource.org/licenses/Apache-2.0 Apache 2.0 License
 */
class Length implements \Caridea\Validate\Rule
{
    /**
     * @var string The operator type
     */
    private $operator;
    /**
     * @var int|int[] The length comparison
     */
    private $length;
    /**
     * @var string The encoding to pass to `mb_strlen`
     */
    private $encoding;
    
    /**
     * Creates a new LengthRule.
     *
     * @param string $operator The operator type
     * @param int|int[] $length The length comparison
     * @param string $encoding The encoding to pass to `mb_strlen`
     */
    protected function __construct($operator, $length, $encoding = 'UTF-8')
    {
        $this->operator = $operator;
        $this->length = $length;
        $this->encoding = $encoding;
    }
    
    /**
     * Validates the provided value.
     *
     * @param mixed $value A value to validate against the rule
     * @return array|string An array of error codes, a single error code, or
     *     null if validation succeeded
     */
    public function apply($value)
    {
        if (!is_string($value)) {
            return 'FORMAT_ERROR';
        }
        $length = mb_strlen($value, $this->encoding);
        switch ($this->operator) {
            case "lt":
                return $length > $this->length ? 'TOO_LONG' : null;
            case "gt":
                return $length < $this->length ? 'TOO_SHORT' : null;
            case "eq":
                if ($length > $this->length) {
                    return 'TOO_LONG';
                } elseif ($length < $this->length) {
                    return 'TOO_SHORT';
                }
                return null;
            case "bt":
                if ($length > $this->length[1]) {
                    return 'TOO_LONG';
                } elseif ($length < $this->length[0]) {
                    return 'TOO_SHORT';
                }
                return null;
        }
    }
    
    /**
     * Gets a rule that requires strings to be no longer than the limit.
     *
     * @param int $length The maximum length
     * @param string $encoding The string encoding
     * @return \Caridea\Validate\Rule\Length the created rule
     */
    public static function max($length, $encoding = 'UTF-8')
    {
        return new Length('lt', (int) $length, $encoding);
    }
    
    /**
     * Gets a rule that requires strings to be no shorter than the limit.
     *
     * @param int $length The minimum length
     * @param string $encoding The string encoding
     * @return \Caridea\Validate\Rule\Length the created rule
     */
    public static function min($length, $encoding = 'UTF-8')
    {
        return new Length('gt', (int) $length, $encoding);
    }
    
    /**
     * Gets a rule that requires strings to be exactly the length of the limit.
     *
     * @param int $length The required length
     * @param string $encoding The string encoding
     * @return \Caridea\Validate\Rule\Length the created rule
     */
    public static function equal($length, $encoding = 'UTF-8')
    {
        return new Length('eq', (int) $length, $encoding);
    }
    
    /**
     * Gets a rule that requires strings to have a minimum and maximum length.
     *
     * @param int $min The minimum length, inclusive
     * @param int $max The maximum length, inclusive
     * @param string $encoding The string encoding
     * @return \Caridea\Validate\Rule\Length the created rule
     */
    public static function between($min, $max, $encoding = 'UTF-8')
    {
        $length = [(int) $min, (int) $max];
        sort($length);
        return new Length('bt', $length, $encoding);
    }
}
