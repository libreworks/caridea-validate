<?hh // strict

namespace Caridea\Validate\Rule;

class Timezone implements \Caridea\Validate\Rule
{
    protected function __construct()
    {
    }
    
    public function apply(mixed $value, mixed $data = []): ?array<mixed>
    {
        return null;
    }

    public static function timezone(): Timezone
    {
        return new Timezone();
    }
}
