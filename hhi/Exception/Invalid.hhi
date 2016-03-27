<?hh // strict

namespace Caridea\Validate\Exception;

class Invalid extends \UnexpectedValueException implements \Caridea\Validate\Exception
{
    public function __construct(array<string,mixed> $errors)
    {
        parent::__construct();
    }

    public function getErrors(): array<string,mixed>
    {
        return [];
    }
}
