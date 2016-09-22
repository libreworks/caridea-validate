<?hh // strict

namespace Caridea\Validate;

class Builder
{
    private Registry $registry;

    public function __construct(?Registry $registry = null)
    {
        $this->registry = $registry ?? new Registry();
    }

    public function register(array<string,mixed> $definitions): this
    {
        return $this;
    }

    public function field(string $name, mixed ...$rules): this
    {
        return $this;
    }

    public function build(mixed $ruleset = null): Validator
    {
        return new Validator([]);
    }

    protected function getRule(mixed $rule, mixed $arg = null): array<Rule>
    {
        return [];
    }
}
