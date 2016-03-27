<?hh // strict

namespace Caridea\Validate;

class SwitchValidator extends Validator
{
    protected string $field = '';

    protected array<string,Validator> $validators = [];
    
    public function __construct(string $field, array<string,Validator> $validators)
    {
        parent::__construct([]);
    }
    
    /**
     * Iterates over the ruleset and collects any error codes.
     *
     * @param object|array $values An object or associative array to validate
     * @return array Associative array of field name to error
     * @throws \InvalidArgumentException if `$values` is null or matching validator
     */
    protected function iterate(mixed $values): array<string,mixed>
    {
        return [];
    }
}
