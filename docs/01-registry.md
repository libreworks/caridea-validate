# The Registry

The `Caridea\Validate\Registry` class is the face of this library. You can use it to register your own validation functions (more on that [later](02-rules.md)), and you primarily use it to declare your validation rules.

```php
$registry = new \Caridea\Validate\Registry();
$b = $registry->builder();
```
## Declaring Rules

An instance of the `Caridea\Validate\Builder` class is what the registry's `builder` method returns. You can define validation rules either programmatically or by loading serialized definitions.

See _[Chapter 2: Rules](02-rules.md)_ for a list of predefined filtering functions as well as how you can define your own custom filter functions.

### Programmatic Declaration

The `Builder` only has one method used for declaring validation rules: the `field` method. The `field` method returns the `Builder` object, providing a fluent interface.

```php
$b->field('name', 'required')
    ->field('email', 'required', 'email')
    ->field('drinks', ['one_of' => [['coffee', 'tea']]])
    ->field('phone', ['max_length' => 10]);
```

The first argument is the name of the field to validate. The remaining arguments are the validation rules you want to declare. Each rule can be specified as:

* a `string` (if it needs no arguments)
* as an associative `array` (where the key is the rule name and the value is either a single argument or an array of arguments)
* as an `object` (where the property is the rule name and the value is either a single argument or an array of arguments)

```php
$b->field('name', 'required') // string
    ->field('email', 'required', ['email' => '']) // string, associative array
    ->field('citizenship', ['one_of' => [['US', 'MX', 'CA']]]) // associative array
    ->field('phone', (object)['max_length' => 10]); // object
```

Therefore, `'required'` is a shorter form of `['required' => []]`, and     `['max_length' => 10]` is a shorter form of `['max_length' => [10]]`.

Once you have declared all of your validation rules, you can invoke the `build` method of the `Builder` to retrieve a `Caridea\Validate\Validator`.

```php
$validator = $b->build();
```

See _[Chapter 3: The Validator and Results](03-results.md)_ for details about using a `Validator`.

### Serialized Declaration

The easiest way to store rules is in JSON format. The builder accepts definitions according to the [LIVR specification](https://github.com/koorchik/LIVR) (see _[Chapter 4: LIVR](04-livr.md)_ for more information).

For example, say you have a file: `rules.json`.

```json
{
    "name": "required",
    "email": ["required", "email"],
    "drinks": {"one_of": [["coffee", "tea"]]},
    "phone": {"max_length": 10},
}
```

You can retrieve a `Caridea\Validate\Validator` like so:

```php
$ruleset = json_decode(file_get_contents('rules.json'));
$validator = $b->build($ruleset);
```

The JSON syntax here is similar to the programmatic approach above. Properties of this object are names of the fields to validate. The values are the validation rules you want to declare. Each rule can be specified as:

* a `string` (if it needs no arguments)
* as an `object` (where the property is the rule name and the value is either a single argument or an array of arguments)

Therefore, `'required'` is a shorter form of `{'required': []}`, and     `{'max_length': 10}` is a shorter form of `{'max_length': [10]}`.

See _[Chapter 3: The Validator and Results](03-results.md)_ for details about using a `Validator`.

## Declaring Aliases

Aliases allow you to specify names for sets of rules. You can also register your own error codes for these aliases. There are two ways to register these, either programmatically or by loading serialized definitions.

### Programmatic Declaration

The `alias` method on the registry has three arguments. The first is the name of the alias. The second is the rule or rules to alias. The third, which is optional, is the error code to return if any of the rules fail (if not specified, it will return codes normally).

```php
$registry->alias('adult_age', ['positive_integer', ['min_number' => 18]], 'WRONG_AGE');
$registry->alias('valid_address', [
    'nested_object' => (object) [
        'country' => 'required',
        'city' => 'required',
        'zip' => 'positive_integer',
    ]
], 'WRONG_ADDRESS');

$b = $registry->builder();
$b->field('name', 'required')
    ->field('age', ['required', 'adult_age'])
    ->field('address', ['required', 'valid_address']);
```

### Serialized Declaration

The `aliasDefinition` method takes a single argument, an object or an associative array that has up to three fields: `"name"`, `"rules"`, and optionally `"error"`.

For example, say you have a file: `aliases.json`.

```json
[
    {
        "name": "valid_address",
        "rules": {
            "nested_object": {
                "country": "required",
                "city": "required",
                "zip": "positive_integer"
            }
        }
    },
    {
        "name": "adult_age",
        "rules": [ "positive_integer", { "min_number": 18 } ],
        "error": "WRONG_AGE"
    }    
]
```

…and another file, `rules.json`:

```json
{
    "name": "required",
    "age": ["required", "adult_age" ],
    "address": ["required", "valid_address"]
}
```

You can register your aliases and retrieve a `Validator` like so:

```php
$aliases = json_decode(file_get_contents('rules.json'));
foreach ($aliases as $alias) {
    $registry->aliasDefinition($alias);
}
$ruleset = json_decode(file_get_contents('rules.json'));
$validator = $b->build($ruleset);
```
