<?hh // strict

namespace Caridea\Validate\Rule;

class Nested implements \Caridea\Validate\Rule, \Caridea\Validate\Draft
{
    protected function __construct(string $operator, mixed $validator, ?string $field = null)
    {
    }

    public function finish(\Caridea\Validate\Builder $builder): \Caridea\Validate\Rule
    {
        return $this;
    }
    
    public function apply(mixed $value, mixed $data = []): ?array<mixed>
    {
        return null;
    }
    
    public static function nestedObject(\stdClass $ruleset): Nested
    {
        return new Nested("nested_object", $ruleset);
    }

    public static function listOf(mixed $rules): Nested
    {
        return new Nested("list", $rules);
    }

    public static function listOfObjects(\stdClass $ruleset): Nested
    {
        return new Nested("list_objects", $ruleset);
    }

    public static function listOfDifferentObjects(string $field, \stdClass $rulesets): Nested
    {
        return new Nested('list_different_objects', $rulesets, $field);
    }
}
