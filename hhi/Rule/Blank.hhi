<?hh // strict

namespace Caridea\Validate\Rule;

class Blank implements \Caridea\Validate\Rule
{
    protected function __construct(string $operator)
    {
    }

    public function apply(mixed $value, mixed $data = []): ?array<mixed>
    {
        return null;
    }
    
    public static function required(): Blank
    {
        return new Blank('required');
    }
    
    public static function notEmpty(): Blank
    {
        return new Blank('empty');
    }
    
    public static function notEmptyList(): Blank
    {
        return new Blank('list');
    }
}
