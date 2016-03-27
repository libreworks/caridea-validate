<?hh // strict

namespace Caridea\Validate;

interface Draft
{
    public function finish(Builder $builder): Rule;
}
