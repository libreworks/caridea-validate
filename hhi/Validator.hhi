<?hh // strict

namespace Caridea\Validate;

class Validator
{
    protected array<string,array<mixed>> $ruleset = [];
    
    public function __construct(array<string,array<mixed>> $ruleset)
    {
    }

    protected function access(mixed $values, string $field): mixed
    {
        return null;
    }

    protected function iterate(mixed $values): array<string,mixed>
    {
        return [];
    }

    public function validate(mixed $values): Result
    {
        return new Result($this->iterate($values));
    }
    
    public function assert(mixed $values): void
    {
    }
}
