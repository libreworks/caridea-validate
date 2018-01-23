# caridea-validate
Caridea is a miniscule PHP application library. This shrimpy fellow is what you'd use when you just want some helping hands and not a full-blown framework.

![](http://libreworks.com/caridea-100.png)

This is its validation library.

It supports [LIVR rules](https://github.com/koorchik/LIVR) with some exceptions. See the Compliance → _LIVR_ section below.

[![Packagist](https://img.shields.io/packagist/v/caridea/validate.svg)](https://packagist.org/packages/caridea/validate)
[![Build Status](https://travis-ci.org/libreworks/caridea-validate.svg)](https://travis-ci.org/libreworks/caridea-validate)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/libreworks/caridea-validate/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/libreworks/caridea-validate/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/libreworks/caridea-validate/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/libreworks/caridea-validate/?branch=master)
[![Documentation Status](http://readthedocs.org/projects/caridea-validate/badge/?version=latest)](http://caridea-validate.readthedocs.io/en/latest/?badge=latest)

## Installation

You can install this library using Composer:

```console
$ composer require caridea/validate
```

* The master branch (version 3.x) of this project requires PHP 7.1 and has no dependencies.
* Version 2.x of this project requires PHP 7.0 and has no dependencies.
* Version 1.x of this project requires PHP 5.5 and has no dependencies.

## Documentation

* Head over to [Read the Docs](http://caridea-validate.readthedocs.io/en/latest/)

## Compliance

Releases of this library will conform to [Semantic Versioning](http://semver.org).

Our code is intended to comply with [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/), and [PSR-4](http://www.php-fig.org/psr/psr-4/). If you find any issues related to standards compliance, please send a pull request!

### LIVR

We fully support the JSON rule format as defined by the LIVR spec. However, we do not support the v0.4 style declaration for the `one_of` and `list_of` rules.

For the most part, we support all rules and their return codes as defined by the spec with some notable exceptions. We did not implement the following rules:

* `trim` – This is part of filtering, not validation.
* `to_lc` – This is part of filtering, not validation.
* `to_uc` – This is part of filtering, not validation.
* `remove` – This is part of filtering, not validation.
* `leave_only` – This is part of filtering, not validation.

We have not implemented aliases (yet?).

We did add an extra validator: `timezone`! It gives the error `WRONG_TIMEZONE` if the string provided isn't a valid timezone identifier.

## Examples

To create a validator from a rule set, you can pass the definitions to the
builder, or you can use the builder procedurally.

```javascript
// rules.json
{
    "name": "required",
    "email": ["required", "email"],
    "drinks": { "one_of": [["coffee", "tea"]] },
    "phone": {"max_length": 10},
}
```
```php
$registry = new \Caridea\Filter\Registry();
$builder = $registry->builder();
$ruleset = json_decode(file_get_contents('rules.json'));
$validator = $builder->build($ruleset);
```
```php
$registry = new \Caridea\Filter\Registry();
$filter = $builder->field('name', 'required')
    ->field('email', 'required', 'email')
    ->field('drinks', ['one_of' => [['coffee', 'tea']]])
    ->field('phone', ['max_length' => 10])
    ->build();
```

You can either inspect the validation results, or throw an exception containing
any errors.
```php
$input = [
    'foo' => 'bar',
    'abc' => '123',
];
$result = $validator->validate($input);
// or
$validator->assert($input);
```

You can register your own custom rules in the `Registry`.
```php
$registry = new \Caridea\Validate\Registry();
$registry->register([
    'credit_card' => ['MyCustomRules', 'getCreditCard'], // a static method
]);
```
