## Collective (Alpha)
Simple collection implementation on top of native arrays.

### Requirements
- PHP 5.4
- [Composer](http://getcomposer.org) and [selvinortiz/collective](https://packagist.org/packages/selvinortiz/collective)

### Install
```sh
composer require selvinortiz/collective
```

### Running Tests
```sh
composer install
sh spec.sh
```

### Usage
```php
// Sample collection for all methods except get() and set()
$items = new Collective([256, 512, 1024, 'Brad', 'Brandon', 'Matt']);
```
#### `get()`
```php
$items = new Collective(['user' => ['name' => 'Brad']]);
$items->get('users.name');
// 'Brad'
```

#### `set()`
```php
$items = new Collection();
$items->set('users.name', 'Matt')->toArray();
// ['users' => ['name' => 'Matt']]
```

#### `count()`
```php
$items->count();
// 6
```

#### `first()`
```php
$items->first();
// 256
$items->first(function($item)
{
    return stripos($item, 'Bra') !== false;
});
// Brad
```

#### `last()`
```php
$items->last(); 
// 'Matt'
$items->last(function($item)
{
    return stripos($item, 'Bra') !== false;
});
// Brandon
```

#### `filter()`
Filters each item in the collection using your own _callable_

```php
$items->filter(function($item)
{
    return is_numeric($item);
})->toArray();
// 256, 512, 1024
```

#### `map()`
Applies your _callable_ to each item in the collection

```php
$items->apply(function($item)
{
    return is_string($item) ? '- '.$item : $item;
})->toArray();
// 256, 512, 1024, '- Brad', '- Brandon', '- Matt'
```

#### `then()`
Chains functions not defined by the _collection_ without breaking the _pipe_

```php
function filterToStrings($items)
{
	return $items->filter(function ($item) { return is_string($item); });
}

function fourCharsOnly($items)
{
	return $items->filter(function ($item) { return strlen($item) == 4; });
}

$items->then('filterToStrings')->then('filterToLength')->toArray();
// 'Brad', 'Matt'
```
