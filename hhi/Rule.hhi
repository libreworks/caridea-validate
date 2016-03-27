<?hh // strict

namespace Caridea\Validate;

interface Rule
{
    /**
     * Validates the provided value.
     *
     * @param mixed $value A value to validate against the rule
     * @param array|object $data The dataset which contains this field
     * @return array An array of error codes or null if validation succeeded
     */
    public function apply(mixed $value, mixed $data = []): ?array<mixed>;
}
