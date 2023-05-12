# Configuration Host

[![Latest Stable Version](https://poser.pugx.org/mr-luke/configuration/v/stable)](https://packagist.org/packages/mr-luke/configuration)
[![Total Downloads](https://poser.pugx.org/mr-luke/configuration/downloads)](https://packagist.org/packages/mr-luke/configuration)
[![License](https://poser.pugx.org/mr-luke/configuration/license)](https://packagist.org/packages/mr-luke/configuration)

![Tests Workflow](https://github.com/mr-luke/configuration/actions/workflows/run-testsuit.yaml/badge.svg)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=mr-luke_configuration&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=mr-luke_configuration)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=mr-luke_configuration&metric=security_rating)](https://sonarcloud.io/summary/new_code?id=mr-luke_configuration)
[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=mr-luke_configuration&metric=reliability_rating)](https://sonarcloud.io/summary/new_code?id=mr-luke_configuration)

This package provides array host (wrapper) package that supports dot notation access and schema validation.

* [Getting Started](#getting-started)
* [Installation](#installation)
* [Usage](#usage)
* [Plans](#plans)

## Getting Started

Good software development follows many patterns and architectures. We design things that depends on many other parts. Some of them are well structured Objects but many times we need to have some configurations. Often we use array as our config host but it can produce mass of unexpected side-effects becasue of one reason - array is not an Object so it can't follow any schema. But what if it can...

During my work I developed a simple wrapper tool that helped me with schema sensitive arrays and I decided to make it a package. I hope you enjoy it!

## Installation

To install through composer, simply put the following in your composer.json file and run `composer update`

```json
{
    "require": {
        "mr-luke/configuration": "~1.0"
    }
}
```
Or use the following command

```bash
composer require "mr-luke/configuration"
```

## Usage

Let's move to a `Schema` class. It's a validation tool with one interface method:

```php
public function check(array $insert, bool $throw = true): bool
```

* `$insert` - This is your array that is a subject of validation
* `$throw`  - This option change behavior of validation

By default `check` method throws an `InvalidArgumentException` when `$insert` doesn't follow schema.

### Step One

Create your Schema array:
```php
$instruction = [
  'first_key'  => 'required|string',
  'second_key' => 'nullable|integer',
  'third_key'  => 'required|float',
];
```

Available rules:
* `required` - given key must not be empty
* `nullable` - given key can be null
* `boolean`  - given key must be boolean type
* `float`    - given key must be float type
* `integer`  - given key must be integer type
* `string`   - given key must be string and can't be other types

### Step Two

Create new instance of `Mrluke\Configuration\Schema`:

```php
$schema = new Schema(array $instruction);
```

Note! From v1.2.0 you can create `Schema` by static method `createFromFile(string $path, bool $json = false)`.

### Step Three

Create new instance of `Mrluke\Configuration\Host` with `Schema` as a dependency and your `$configArray` is automatically validated.

```php
$host = new Host($configArray, $schema);
```

If your `$configArray` doesn't follow given `Schema`, you will get `InvalidArgumentException`. You can also use `Host` without any `Schema` due to it's optional parameter of `Mrluke\Configuration\Host`.

### Your configuration is Wrapped!

Now you have an access to `Host` methods:

```php
/**
 * Return given key from array.
 *
 * @param  string $key
 * @param  mixed  $default
 * @return mixed
 */
public function get(string $key, $default = null)
```

Your `key` can follow **dot notation** to access nested `keys`:
```php
$host->get('mysql.database', 'my_db');
```

By default if `key` is not present, `Host` returns `null`. You can also use magic getter to acces config:
```php
$host->mysql;
```

You can check if given hey is present:
```php
/**
 * Determine if given key is present.
 *
 * @param  string $key
 * @return bool
 */
public function has(string $key): bool
```

## Plans

Feel free to contribute because I am aware that there are some things to improve. For now:
* Nested Schema support
* New validation rules support
* New Schema's file format
