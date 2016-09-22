<?hh // decl

namespace Caridea\Validate;

class Registry
{
    public function __construct()
    {
    }

    public function register(array<string,mixed> $definitions): this
    {
        return $this;
    }

    public function builder(): Builder
    {
        return new Builder($this);
    }

    public function factory(string $rule, mixed $arg = null): Rule
    {
    }
}
