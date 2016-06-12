## Collective (Alpha)
Simple collection implementation on top of native arrays.

### Requirements
- PHP 5.4
- [Composer](http://getcomposer.org) and [selvinortiz/collective](https://packagist.org/packages/selvinortiz/collective)

### Install
```sh
composer require selvinortiz/collective
```

### Usage
```php
$arr = new Collective([1, 101, 666, 'Brad', 'Brandon', 'Matt']);
```

#### `count()`
```php
$arr->count();
// 6
```

#### `first()`
```php
$arr->first();
// 1
$arr->first(function($value)
{
    return stripos($value, 'Bra') !== false;
});
// Brad
```

#### `last()`
```php
$arr->last(); 
// 'Matt'
$arr->last(function($value)
{
    return stripos($value, 'Bra') !== false;
});
// Brandon
```

#### `filter()`
```php
$arr->filter(function($value)
{
    return is_numeric($value);
})->toArray();
// 1, 101, 666
```

#### `apply()`
```php
$arr->apply(function($value)
{
    return is_string($value) ? '- '.$value : $value;
})->toArray();
// 1, 101, 666, '- Brad', '- Brandon', '- Matt'
```


## Running Tests
```sh
composer install
sh spec.sh
```
