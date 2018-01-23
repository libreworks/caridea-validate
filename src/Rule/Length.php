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
namespace Caridea\Validate\Rule;

/**
 * Compares string length to accepted boundaries.
 *
 * @copyright 2015-2018 LibreWorks contributors
 * @license   Apache-2.0
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
    protected function __construct(string $operator, $length, string $encoding = 'UTF-8')
    {
        $this->operator = $operator;
        $this->length = $length;
        $this->encoding = $encoding;
    }

    /**
     * {@inheritDoc}
     */
    public function apply($value, $data = []): ?array
    {
        if (!is_string($value)) {
            return ['FORMAT_ERROR'];
        }
        $length = mb_strlen($value, $this->encoding);
        switch ($this->operator) {
            case "lt":
                return $length > $this->length ? ['TOO_LONG'] : null;
            case "gt":
                return $length < $this->length ? ['TOO_SHORT'] : null;
            case "eq":
                if ($length > $this->length) {
                    return ['TOO_LONG'];
                } elseif ($length < $this->length) {
                    return ['TOO_SHORT'];
                }
                return null;
            case "bt":
                if ($length > $this->length[1]) {
                    return ['TOO_LONG'];
                } elseif ($length < $this->length[0]) {
                    return ['TOO_SHORT'];
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
    public static function max(int $length, string $encoding = 'UTF-8'): Length
    {
        return new Length('lt', $length, $encoding);
    }

    /**
     * Gets a rule that requires strings to be no shorter than the limit.
     *
     * @param int $length The minimum length
     * @param string $encoding The string encoding
     * @return \Caridea\Validate\Rule\Length the created rule
     */
    public static function min(int $length, string $encoding = 'UTF-8'): Length
    {
        return new Length('gt', $length, $encoding);
    }

    /**
     * Gets a rule that requires strings to be exactly the length of the limit.
     *
     * @param int $length The required length
     * @param string $encoding The string encoding
     * @return \Caridea\Validate\Rule\Length the created rule
     */
    public static function equal(int $length, string $encoding = 'UTF-8'): Length
    {
        return new Length('eq', $length, $encoding);
    }

    /**
     * Gets a rule that requires strings to have a minimum and maximum length.
     *
     * @param int $min The minimum length, inclusive
     * @param int $max The maximum length, inclusive
     * @param string $encoding The string encoding
     * @return \Caridea\Validate\Rule\Length the created rule
     */
    public static function between(int $min, int $max, string $encoding = 'UTF-8'): Length
    {
        $length = [$min, $max];
        sort($length);
        return new Length('bt', $length, $encoding);
    }
}
