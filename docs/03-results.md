# The Validator and Results

A `Validator` is just an immutable container for all of the validation rules you've defined with a `Builder`. Once you've retrieved a `Validator` from the `Builder`, you can validate input to produce a validation result.

Let's use some example rules and get a `Validator`.

```php
$registry = new \Caridea\Validate\Registry();
$b = $registry->builder();
$validator = $b->field('name', 'required')
    ->field('email', 'required', 'email')
    ->field('drinks', ['one_of' => [['coffee', 'tea']]])
    ->field('phone', ['max_length' => 10])
    ->build();
```

## Validating Input

Let's take some user input and produce some validation results, which come back as a `Caridea\Validate\Result` object.

```php
// let's pretend this came from $_POST
$input = [
    'name' => 'john smith',
    'email' => 'jsmith@example.com',
    'drinks' => 'tea',
    'phone' => '8005551234'
];

$result = $validator->validate($input);
echo $result->hasErrors() ? 'bad' : 'good';
```

…gives us:

```
good
```

Now, let's try the same rules on bad input.

```php
// let's pretend this came from $_POST
$input = [
    'name' => null,
    'email' => 'jsmith',
    'drinks' => 'cola',
    'phone' => '00000000000000000'
];

$result = $validator->validate($input);
echo $result->hasErrors() ? 'bad' : 'good'; // prints "good"
echo PHP_EOL, $result, PHP_EOL;
```

…gives us:

```
bad
{"name":"REQUIRED","email":"WRONG_EMAIL","drinks":"NOT_ALLOWED_VALUE","phone":"TOO_LONG"}
```

Let's try it on nested rules.

```php
$b = $registry->builder();
$validator = $b->field('name', 'required')
    ->field('foo', ['nested_object' => [(object)['bar' => 'required']]])
    ->build();

// let's pretend this came from $_POST
$input = [
    'name' => null,
    'foo' => [
        'bar' => null,
    ]
];

$result = $validator->validate($input);
echo $result->hasErrors() ? 'bad' : 'good'; // prints "good"
echo PHP_EOL, $result, PHP_EOL;
```

…gives us:

```
bad
{"name":"REQUIRED","foo":{"bar":"REQUIRED"}}
```

## Asserting Input

The `Validator` has an `assert` method that does nothing if validation passes, but throws an exception if it fails.

```php
// let's pretend this came from $_POST
$input = [
    'name' => null,
    'email' => 'jsmith',
    'drinks' => 'cola',
    'phone' => '00000000000000000'
];

try {
    $validator->validate($input);
} catch (\Caridea\Validate\Exception\Invalid $e) {
    $errors = $e->getErrors();
    var_dump($errors);
}
```

…gives us:

```
array(4) {
  'name' =>
  string(8) "REQUIRED"
  'email' =>
  string(11) "WRONG_EMAIL"
  'drinks' =>
  string(17) "NOT_ALLOWED_VALUE"
  'phone' =>
  string(8) "TOO_LONG"
}
```
