<?hh // strict

namespace Caridea\Validate;

class Result
{
    public function __construct(array<string,mixed> $errors)
    {
    }
    
    public function hasErrors(): bool
    {
        return false;
    }

    public function getErrors(): array<string,mixed>
    {
        return [];
    }

    public function __toString(): string
    {
        return '';
    }
}
