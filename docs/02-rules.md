# Predefined and Custom Validation Rules

This chapter will detail all of the available validation functions predefined in the `Registry`, as well as the means by which you can construct and register your own functions.

## Provided Functions

The `Registry` has a number of functions that you can use out of the box. Almost all of these are defined in the LIVR specification.

### Standard Rules
* `required` – Fails if a value is `null` or an empty string
* `not_empty` – Fails if a value is an empty string
* `not_empty_list` – Fails if a `count` returns `0`
* `any_object` – Fails if a value is not an `object` or an associative `array`
* `string` – Fails if a value is not a `string`, `int`, `float`, or `bool`
* `one_of` – Fails if a value is not in a list of acceptable choices
* `eq` – Fails if a value is not equal to the provided value (like `one_of` with only one option)
* `min_length` – Fails if a string is shorter than an acceptable length
* `max_length` – Fails if a string is longer than an acceptable length
* `length_equal` – Fails if a string is not the exact specified length
* `length_between` – Fails if a string is shorter or longer than the specified range
* `like` – Fails if a string doesn't match a regular expression
* `integer` – Fails if a value does not evaluate to an integer
* `positive_integer` – Fails if a value does not evaluate to a positive integer
* `decimal` – Fails if a value does not evaluate to a decimal
* `positive_decimal` – Fails if a value does not evaluate to a positive decimal
* `min_number` – Fails if a number is less than a lower bound
* `max_number` – Fails if a number is greater than an upper bound
* `number_between` – Fails if a number does not fall within the specified range
* `email` – Fails if a string is not a valid email address
* `iso_date` – Fails if a string is not a valid ISO 8601 date (e.g. `2018-01-01`)
* `url` – Fails if a string is not a valid URL
* `equal_to_field` – Fails if a value is not equal to a different field on the same object under validation
* `timezone` – Fails if a string is not a valid timezone identifier (e.g. `America/New_York`) (this rule is not part of the LIVR specification)

### Meta-rules
* `nested_object` – Allows you to specify rules for a nested object
* `variable_object` – Allows you to specify rules for a nested object, but also declare alternate rules depending on the value of an object property
* `list_of` – Allows you to specify rules for the elements of a list
* `list_of_objects` – Allows you to specify rules for objects within a list
* `list_of_different_objects` – Allows you to specify rules for objects within a list, but also declare alternate rules depending on the value of an object property

## Custom Functions

The `Registry` class has a `register` method that accepts an `array`. By invoking this method, you can register your own custom validation functions. The array keys are the names of your functions, and each value should be a `callable` factory that accepts zero or more arguments and produces a `Caridea\Validate\Rule` object.

### The Rule class

The `Rule` interface requires only a single method: `apply`, which takes the value to validate and also the original object from which it came. This method needs to return `null` if all validation is successful, or an `array` of `string` error codes.

### Example

Let's declare and register a sample validation `Rule` for credit card numbers.

```php
/**
 * Luhn algorithm number checker
 *
 * This code has been released into the public domain, however please give
 * credit to the original author where possible.
 *
 * @author shaman - www.planzero.org
 */
function luhn_check($number)
{
    $number = preg_replace('/\D/', '', $number);
    $number_length = strlen($number);
    $parity = $number_length % 2;
    $total = 0;
    for ($i = 0; $i < $number_length; $i++) {
        $digit = $number[$i];
        if ($i % 2 == $parity) {
            $digit *= 2;
            if ($digit > 9) {
                $digit -= 9;
            }
        }
        $total += $digit;
    }
    return ($total % 10 == 0);
}

class MyCustomRules
{
    public static function getCreditCard()
    {
        return new class() implements \Caridea\Validate\Rule
        {
            public function apply($value, $data = []): ?array
            {
                if (!is_string($value)) {
                    return ["FORMAT_ERROR"];
                }
                return luhn_check($value) ? null : ['WRONG_CREDIT_CARD'];
            }
        }
    }
}

$registry = new \Caridea\Validate\Registry();
$registry->register([
    'credit_card' => ['MyCustomRules', 'getCreditCard'], // a static method
]);
```
