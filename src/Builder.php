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
 * Builds validators.
 *
 * @copyright 2015-2018 LibreWorks contributors
 * @license   Apache-2.0
 */
class Builder
{
    /**
     * @var \Caridea\Validate\Parser
     */
    private $parser;
    /**
     * @var array<string,\Caridea\Validate\Rule\Set>
     */
    private $validators = [];

    /**
     * Creates a new Validation Builder.
     *
     * @param \Caridea\Validate\Parser $parser The parser.
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Adds one or more rules to this builder.
     *
     * @param string $field The field to validate
     * @param string|object|array $rules Either a string name, an associative
     *        array, or an object with name → arguments
     * @return $this provides a fluent interface
     */
    public function field(string $field, ...$rules): self
    {
        $this->validators[$field] = $this->parser->parse($rules);
        return $this;
    }

    /**
     * Builds a validator for the provided ruleset.
     *
     * ```javascript
     * // rules.json
     * {
     *     name: 'required',
     *     email: ['required', 'email'],
     *     drinks: { one_of: [['coffee', 'tea']] },
     *     phone: {max_length: 10}
     * }
     * ```
     * ```php
     * $ruleset = json_decode(file_get_contents('rules.json'));
     * $builder = new \Caridea\Validate\Builder();
     * $validator = $builder->build($ruleset);
     * ```
     *
     * @param object|array $ruleset Object or associative array (as returned
     *        from `json_decode`) with ruleset, or `null` to use defined rules.
     * @return \Caridea\Validate\Validator the built validator
     */
    public function build($ruleset = null): Validator
    {
        $validators = array_merge([], $this->validators);
        if (is_object($ruleset) || (is_array($ruleset) && Parser::isAssociative($ruleset))) {
            foreach ($ruleset as $field => $rules) {
                $validators[$field] = $this->parser->parse($rules);
            }
        }
        return new Validator($validators);
    }
}
