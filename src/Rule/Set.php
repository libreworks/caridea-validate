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
 * A set of several Rule objects.
 *
 * @copyright 2015-2018 LibreWorks contributors
 * @license   Apache-2.0
 */
class Set implements \Caridea\Validate\Rule, \IteratorAggregate, \Countable
{
    /**
     * @var \Caridea\Validate\Rule[] The rules
     */
    private $rules = [];
    /**
     * @var string Optional error code to return
     */
    private $error;

    /**
     * Creates a new Set.
     *
     * @param \Caridea\Validate\Rule[] $rules The rules to add
     * @param string|null $error Optional error code to return
     * @throws \InvalidArgumentException if `$rules` contains invalid types
     */
    public function __construct(array $rules = [], ?string $error = null)
    {
        $this->addAll($rules);
        $this->error = $error;
    }

    /**
     * Adds one or more `Rule`s into this `Set`.
     *
     * @param \Caridea\Validate\Rule ...$rules  The rules to add
     * @return $this Provides a fluent interface
     */
    public function add(\Caridea\Validate\Rule ...$rules): self
    {
        $this->rules = array_merge($this->rules, $rules);
        return $this;
    }

    /**
     * Adds several `Rule`s into this `Set`.
     *
     * @param \Caridea\Validate\Rule[] $rules The rules to add
     * @return $this Provides a fluent interface
     * @throws \InvalidArgumentException if `$rules` contains invalid types
     */
    public function addAll(array $rules): self
    {
        try {
            return $this->add(...$rules);
        } catch (\TypeError $e) {
            throw new \InvalidArgumentException('Only Rule objects are allowed', 0, $e);
        }
    }

    /**
     * Adds the entries from another `Set` into this one.
     *
     * @param \Caridea\Validate\Rule\Set $rules The rules to add
     * @return $this Provides a fluent interface
     */
    public function merge(Set $rules): self
    {
        $this->rules = array_merge($this->rules, $rules->rules);
        return $this;
    }

    /**
     * Gets the error code, or `null`
     *
     * @return string|null The error code or `null`
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * Sets the error code.
     *
     * @param string|null $error The error code or `null`
     * @return $this provides a fluent interface
     */
    public function setError(?string $error): self
    {
        $this->error = $error;
        return $this;
    }

    /**
     * Count elements of an object
     *
     * @return int Custom count as an integer
     */
    public function count(): int
    {
        return count($this->rules);
    }

    /**
     * Retrieve an external iterator
     *
     * @return \Traversable An instance of an object implementing Iterator or Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->rules);
    }

    /**
     * {@inheritDoc}
     */
    public function apply($value, $data = []): ?array
    {
        $errors = [];
        $empty = $value === null || $value === '';
        foreach ($this->rules as $rule) {
            $error = (!$empty || $rule instanceof Blank) ?
                $rule->apply($value, $data) : null;
            if ($error !== null) {
                $errors = $error;
                break;
            }
        }
        return empty($errors) ? null : ($this->error !== null ? [$this->error] : $errors);
    }
}
