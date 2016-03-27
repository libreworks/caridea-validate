<?hh // strict

namespace Caridea\Validate\Rule;

class Match implements \Caridea\Validate\Rule
{
    const string URL = "_^(?:https?://)(?:\\S+(?::\\S*)?@)?(?:(?:[1-9]\\d?|1\\d\\d|2[01]\\d|22[0-3])(?:\\.(?:1?\\d{1,2}|2[0-4]\\d|25[0-5])){2}(?:\\.(?:[1-9]\\d?|1\\d\\d|2[0-4]\\d|25[0-4]))|(?:(?:[a-z\\x{00a1}-\\x{ffff}0-9]-*)*[a-z\\x{00a1}-\\x{ffff}0-9]+)(?:\\.(?:[a-z\\x{00a1}-\\x{ffff}0-9]-*)*[a-z\\x{00a1}-\\x{ffff}0-9]+)*(?:\\.(?:[a-z\\x{00a1}-\\x{ffff}]{2,}))\\.?)(?::\\d{2,5})?(?:[/?#]\\S*)?\$_iuS";
    const string EMAIL = '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/';
    const string DATE = '/^(\d{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/';
    
    public function __construct(string $pattern, ?string $error = null)
    {
    }

    public function apply(mixed $value, mixed $data = []): ?array<mixed>
    {
        return null;
    }
    
    public static function like(string $pattern, string $flags = ''): Match
    {
        return new Match("/$pattern/$flags", 'WRONG_FORMAT');
    }

    public static function url(): Match
    {
        return new Match(self::URL, 'WRONG_URL');
    }

    public static function email(): Match
    {
        return new Match(self::EMAIL, 'WRONG_EMAIL');
    }

    public static function isoDate(): Match
    {
        return new Match(self::DATE, 'WRONG_DATE');
    }
}
