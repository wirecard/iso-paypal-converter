# ISO 3166-2 to PayPal Converter

Converts [ISO 3166-2 alpha 2 codes](https://en.wikipedia.org/wiki/ISO_3166-2) to the [codes the PayPal API requires](https://developer.paypal.com/docs/classic/api/state_codes/) to identify your customers state.

[![License](https://img.shields.io/badge/license-GPLv3-blue.svg)](https://github.com/wirecard/iso-paypal-converter/blob/master/LICENSE)
[![PHP v5.6](https://img.shields.io/badge/php-v5.6-yellow.svg)](http://www.php.net)
[![PHP v7.0](https://img.shields.io/badge/php-v7.0-yellow.svg)](http://www.php.net)
[![PHP v7.1](https://img.shields.io/badge/php-v7.1-yellow.svg)](http://www.php.net)

## Installation

The library can be installed using [Composer](https://getcomposer.org/download/).  
If you have not installed Composer, you can follow the [offical instructions](https://getcomposer.org/doc/00-intro.md).

Once composer is installed, run this in your terminal/command-line tool:

`composer require wirecard/iso-paypal-converter`

## Usage

In your application load the `vendor/autoload.php` that Composer provides.   
You can then initialize the `Converter` class like so:

```php
use Wirecard\IsoToPayPal\Converter;

$converter = new Converter();
```

This automatically loads all the state mappings.

### Conversion

To convert an ISO 3166-2 code to the correct PayPal identifier, you need only pass the country code
and state identifier to the convert function like so: 

```php
$converter->convert("TH", "50");
// => "Chiang Mai"
```

Alternatively you can pass in a fully formed ISO 3166-2 code with state identifier like so:

```php
$converter->convert("JP-01");
// => "HOKKAIDO"
```

### Exceptions

Note that if you omit the state parameter, the first argument **must** include a state identifer,
otherwise you will receive an `InvalidArgumentException`:

```php
$converter->convert("US");
// => InvalidArgumentException
```

A fully formed ISO 3166-2 code takes the form `XX-YY`.

If a country is not found in the conversion table, you will receive a `CountryNotFoundException`:

```php
$converter->convert("AT-1");
// => CountryNotFoundException
```

And finally, if the state cannot be found in the country you stated, you will receive 
a `StateNotFoundException` like so:

```php
$converter->convert("CA-NY");
// => StateNotFoundException

$converter->convert("US-NY");
// => "NY"
```