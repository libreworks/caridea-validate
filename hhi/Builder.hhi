<?hh // strict

namespace Caridea\Validate;

class Builder
{
    public function __construct()
    {
    }

    public function register(array<string,mixed> $definitions): this
    {
        return $this;
    }
    
    public function build(\stdClass $ruleset): Validator
    {
        return new Validator([]);
    }

    protected function getRule(mixed $rule, mixed $arg = null): array<Rule>
    {
        return [];
    }
}
