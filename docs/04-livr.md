# Language Independent Validation Rules

[Language Independent Validation Rules](https://github.com/koorchik/LIVR), or LIVR, is a simple specification for validation rules. We adhere to nearly all of version 2.0 of the specification, with a couple of exceptions.

## No Short Argument Parsing
We fully support the JSON rule format as defined by the LIVR spec. However, we do not support the v0.4 style declaration for the `one_of` and `list_of` rules. While it does seem like a shortcut to omit the wrapping array, it means that we have to account for rule implementations while parsing definitions, which seems not quite right to us.

## No Or
The `or` rule is noted as experimental in the specification, and we have omitted an implementation at this time.

## No Modifiers
For the most part, we support all rules and their return codes as defined by the spec with some notable exceptions. We did not implement any of the _Modifier_ rules. They're part of filtering and not validation. Values should be sanitized before they even _reach_ validation. Have a look at the [caridea-filter](https://github.com/libreworks/caridea-filter) library if you need filtering.

The definitions we excluded from this library include:

* `trim`
* `to_lc`
* `to_uc`
* `remove`
* `leave_only`
* `default`
