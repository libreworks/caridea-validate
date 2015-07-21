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
namespace Caridea\Bind\Validate\Rule;

/**
 * Pattern matching rule.
 *
 * @copyright 2015 LibreWorks contributors
 * @license   http://opensource.org/licenses/Apache-2.0 Apache 2.0 License
 */
class Match implements \Caridea\Bind\Validate\Rule
{
    /**
     * @var string The pattern to match
     */
    private $pattern;
    /**
     * @var string The failure error code
     */
    private $error;

    /**
     * Modified version of the Regular Expression for URL validation.
     * 
     * @var string Regular Expression to match URLs
     * @copyright 2010-2013 Diego Perini (http://www.iport.it)
     * @license http://opensource.org/licenses/MIT MIT License
     * @link https://gist.github.com/dperini/729294 GitHub gist
     */
    const URL = "_^(?:https?://)(?:\\S+(?::\\S*)?@)?(?:(?:[1-9]\\d?|1\\d\\d|2[01]\\d|22[0-3])(?:\\.(?:1?\\d{1,2}|2[0-4]\\d|25[0-5])){2}(?:\\.(?:[1-9]\\d?|1\\d\\d|2[0-4]\\d|25[0-4]))|(?:(?:[a-z\\x{00a1}-\\x{ffff}0-9]-*)*[a-z\\x{00a1}-\\x{ffff}0-9]+)(?:\\.(?:[a-z\\x{00a1}-\\x{ffff}0-9]-*)*[a-z\\x{00a1}-\\x{ffff}0-9]+)*(?:\\.(?:[a-z\\x{00a1}-\\x{ffff}]{2,}))\\.?)(?::\\d{2,5})?(?:[/?#]\\S*)?\$_iuS";
    /**
     * RegEx taken from HTML5 spec for email input type.
     * 
     * @var string Regular Expression to match e-mails
     * @link http://www.w3.org/TR/html5/forms.html#e-mail-state-%28type=email%29 HTML5 Form Specification
     */
    const EMAIL = '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/';
    /**
     * A "pretty good" regex for ISO 8601 Dates
     * 
     * @var string Regular Expression to match dates
     */
    const DATE = '/^(\d{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/';
    
    /**
     * Creates a new MatchRule.
     * 
     * @param string $pattern The pattern to match
     * @param string $error The failure error code
     */
    public function __construct($pattern, $error)
    {
        $this->pattern = (string) $pattern;
        $this->error = strlen(trim($error)) > 0 ? (string) $error : 'WRONG_FORMAT';
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
        return preg_match($this->pattern, $value) ? null : $this->error;
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
     * @return \Caridea\Bind\Validate\Rule\Match the created rule
     */
    public static function like($pattern, $flags = '')
    {
        return new Match("/$pattern/$flags", 'WRONG_FORMAT');
    }

    /**
     * Gets a rule that matches URLs.
     * 
     * @return \Caridea\Bind\Validate\Rule\Match the created rule
     */
    public static function url()
    {
        return new Match(self::URL, 'WRONG_URL');
    }

    /**
     * Gets a rule that matches e-mail addresses.
     * 
     * @return \Caridea\Bind\Validate\Rule\Match the created rule
     */
    public static function email()
    {
        return new Match(self::EMAIL, 'WRONG_EMAIL');
    }
    
    /**
     * Gets a rule that matches ISO 8601 dates.
     * 
     * @return \Caridea\Bind\Validate\Rule\Match the created rule
     */
    public static function isoDate()
    {
        return new Match(self::DATE, 'WRONG_DATE');
    }
}
