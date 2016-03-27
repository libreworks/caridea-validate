<?hh // strict

namespace Caridea\Validate\Rule;

class Length implements \Caridea\Validate\Rule
{
    protected function __construct(string $operator, mixed $length, string $encoding = 'UTF-8')
    {
    }

    public function apply(mixed $value, mixed $data = []): ?array<mixed>
    {
        return null;
    }

    public static function max(int $length, string $encoding = 'UTF-8'): Length
    {
        return new Length('lt', $length, $encoding);
    }

    public static function min(int $length, string $encoding = 'UTF-8'): Length
    {
        return new Length('gt', $length, $encoding);
    }

    public static function equal(int $length, string $encoding = 'UTF-8'): Length
    {
        return new Length('eq', $length, $encoding);
    }

    public static function between(int $min, int $max, string $encoding = 'UTF-8'): Length
    {
        return new Length('bt', null, $encoding);
    }
}
