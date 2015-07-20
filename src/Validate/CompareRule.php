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
 * Compares scalar values to some operand.
 *
 * @copyright 2015 LibreWorks contributors
 * @license   http://opensource.org/licenses/Apache-2.0 Apache 2.0 License
 */
class CompareRule implements Rule
{
    /**
     * @var string The operator type
     */
    private $operator;
    /**
     * @var mixed The comparison value
     */
    private $operand;

    /**
     * Modified version of the Regular Expression for URL validation.
     * 
     * @var string Regular Expression to match URLs
     * @copyright 2010-2013 Diego Perini (http://www.iport.it)
     * @license http://opensource.org/licenses/MIT MIT License
     * @link https://gist.github.com/dperini/729294 GitHub gist
     */
    private static $urlPattern = "_^(?:https?://)(?:\\S+(?::\\S*)?@)?(?:(?:[1-9]\\d?|1\\d\\d|2[01]\\d|22[0-3])(?:\\.(?:1?\\d{1,2}|2[0-4]\\d|25[0-5])){2}(?:\\.(?:[1-9]\\d?|1\\d\\d|2[0-4]\\d|25[0-4]))|(?:(?:[a-z\\x{00a1}-\\x{ffff}0-9]-*)*[a-z\\x{00a1}-\\x{ffff}0-9]+)(?:\\.(?:[a-z\\x{00a1}-\\x{ffff}0-9]-*)*[a-z\\x{00a1}-\\x{ffff}0-9]+)*(?:\\.(?:[a-z\\x{00a1}-\\x{ffff}]{2,}))\\.?)(?::\\d{2,5})?(?:[/?#]\\S*)?\$_iuS";
    /**
     * RegEx taken from HTML5 spec for email input type.
     * 
     * @var string Regular Expression to match e-mails
     * @link http://www.w3.org/TR/html5/forms.html#e-mail-state-%28type=email%29 HTML5 Form Specification
     */
    private static $emailPattern = '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/';
    
    /**
     * Creates a new CompareRule.
     * 
     * @param string $operator The operator type
     * @param mixed $operand Optional comparison value
     */
    protected function __construct($operator, $operand = null)
    {
        $this->operator = $operator;
        $this->operand = $operand;
    }
    
    /**
     * Validates the provided value.
     * 
     * @param mixed $value A value to validate against the rule
     * @return string An error code, or null if validation succeeded
     */
    public function apply($value)
    {
        if (!is_scalar($value)) {
            return 'FORMAT_ERROR';
        }
        switch ($this->operator) {
            case "re":
                return preg_match($this->operand, $value) ? null : 'WRONG_FORMAT';
            case "in":
                return in_array($value, $this->operand) ? null : 'NOT_ALLOWED_VALUE';
            case "dt":
                $matched = [];
                if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value, $matched)) {
                    $d = \DateTimeImmutable::createFromFormat('Y-m-d', $value);
                    if ($d->format('Y-m-d') === $value) {
                        return null;
                    }
                }
                return 'WRONG_DATE';
            case "url":
                return preg_match(self::$urlPattern, $value) ? null : 'WRONG_URL';
            case "email":
                return preg_match(self::$emailPattern, $value) ? null : 'WRONG_EMAIL';
        }
    }
    
    /**
     * Gets a regular expression matching rule.
     * 
     * ```php
     * $rule = CompareRule::matches("^[a-z]$", "i");
     * ```
     * 
     * @param string $pattern An unbounded regular expression (no delimiters!)
     * @param string $flags Any PCRE regex flags (e.g. i, s)
     * @return \Caridea\Bind\Validate\CompareRule the created rule
     */
    public static function matches($pattern, $flags = '')
    {
        return new CompareRule('re', "/$pattern/$flags");
    }

    /**
     * Gets a rule that matches a value against a list of accepted values.
     * 
     * @param array $values The accepted values
     * @return \Caridea\Bind\Validate\CompareRule the created rule
     */
    public static function oneOf(array $values)
    {
        return new CompareRule('in', $values);
    }
    
    /**
     * Gets a rule that matches ISO 8601 dates.
     * 
     * @return \Caridea\Bind\Validate\CompareRule the created rule
     */
    public static function isoDate()
    {
        return new CompareRule('dt');
    }
    
    /**
     * Gets a rule that matches URLs.
     * 
     * @return \Caridea\Bind\Validate\CompareRule the created rule
     */
    public static function url()
    {
        return new CompareRule('url');
    }
    
    /**
     * Gets a rule that matches e-mail addresses.
     * 
     * @return \Caridea\Bind\Validate\CompareRule the created rule
     */
    public static function email()
    {
        return new CompareRule('email');
    }
}
