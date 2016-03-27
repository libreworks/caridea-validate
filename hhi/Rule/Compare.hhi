<?hh // strict

namespace Caridea\Validate\Rule;

class Compare implements \Caridea\Validate\Rule
{
    protected function __construct(string $operator, mixed $operand = null)
    {
    }
    
    public function apply(mixed $value, mixed $data = []): ?array<mixed>
    {
        return null;
    }

    protected function access(mixed $values, string $field): mixed
    {
        return null;
    }

    public static function oneOf(array<mixed> $values): Compare
    {
        return new Compare('in', $values);
    }

    public static function max(num $value): Compare
    {
        return new Compare('lt', $value);
    }

    public static function min(num $value): Compare
    {
        return new Compare('gt', $value);
    }

    public static function between(num $min, num $max): Compare
    {
        $value = $min > $max ? [$max, $min] : [$min, $max];
        return new Compare('bt', $value);
    }

    public static function integer(): Compare
    {
        return new Compare('int');
    }

    public static function positiveInteger(): Compare
    {
        return new Compare('+int');
    }

    public static function decimal(): Compare
    {
        return new Compare('float');
    }

    public static function positiveDecimal(): Compare
    {
        return new Compare('+float');
    }
    
    public static function equalToField(string $field): Compare
    {
        return new Compare('eqf', $field);
    }
}
