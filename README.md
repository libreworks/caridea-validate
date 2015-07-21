# caridea-validate
Caridea is a miniscule PHP application library. This shrimpy fellow is what you'd use when you just want some helping hands and not a full-blown framework.

![](http://libreworks.com/caridea-100.png)

This is its validation library.

It supports [LIVR rules](https://github.com/koorchik/LIVR) with some exceptions. See the Compliance → _LIVR_ section below.

## Installation

You can install this library using Composer:

```console
$ composer require caridea/validate
```

This project requires PHP 5.5 and has no dependencies.

## Compliance

Releases of this library will conform to [Semantic Versioning](http://semver.org).

Our code is intended to comply with [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/), and [PSR-4](http://www.php-fig.org/psr/psr-4/). If you find any issues related to standards compliance, please send a pull request!

### LIVR

We fully support the JSON rule format as defined by the LIVR spec. However, we do not support the v0.4 style declaration for the `one_of` and `list_of` rules.

For the most part, we support all rules and their return codes as defined by the spec with some notable exceptions. We did not implement the following rules:

* `equal_to_field` – In our opinion, this is only used for password fields and should not be part of validation logic.
* `trim` – This is part of filtering, not validation. 
* `to_lc` – This is part of filtering, not validation. 
* `to_uc` – This is part of filtering, not validation. 
* `remove` – This is part of filtering, not validation. 
* `leave_only` – This is part of filtering, not validation. 

We have not implemented aliases (yet?).

## Examples

Just a few quick examples.
