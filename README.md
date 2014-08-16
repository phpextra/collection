#Common classes and interfaces for PHP

[![Latest Stable Version](https://poser.pugx.org/phpextra/collection/v/stable.svg)](https://packagist.org/packages/phpextra/collection)
[![Total Downloads](https://poser.pugx.org/phpextra/collection/downloads.svg)](https://packagist.org/packages/phpextra/collection)
[![License](https://poser.pugx.org/phpextra/collection/license.svg)](https://packagist.org/packages/phpextra/collection)
[![Build Status](http://img.shields.io/travis/phpextra/collection.svg)](https://travis-ci.org/phpextra/collection)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/phpextra/collection/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/phpextra/collection/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/phpextra/collection/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/phpextra/collection/?branch=master)
[![GitTip](http://img.shields.io/gittip/jkobus.svg)](https://www.gittip.com/jkobus)

##Usage

###Collection (PHPExtra\Type\Collection)

Collection solve few things by implementing following interfaces: ``\Countable``, ``\ArrayAccess``, ``\Iterator``, and ``\SortableInterface``.
This gives you an ability to ``count()`` collection, use a ``foreach`` on it, access it like an array ``$a[1]`` and sort its contents ``$a->sort($sorter)``.
Apart from regular collections there are also ``LazyCollection``'s that allow you to specify a closure that will initialize collection
contents only if it's needed.

Create your first collection:

```php
$collection = new Collection();

$collection->add('item1');
$collection->add('item2');
$collection->add('item3);
```

Use it:

```php
echo count($collection); // returns 3
echo $collection[0]; // returns "item1"
echo $collection->slice(1, 2); // returns Collection with a length of 2 containing item2 and item3.
echo $collection->filter(function($element, $offset){ return $offset % 2 == 0; }); // returns sub-collection with all elements with even offset number
$collection->sort(SorterInterface $sorter); // sorts collection
```

Lazy collection example:

```php
$lazy = new LazyCollection(function(){
    return new Collection(array(1, 2, 3));
});

echo $lazy[2]; // initializes the closure and returns "3"
```
## Installation (Composer)

```json
{
    "require": {
        "phpextra/collection":"~1.0"
    }
}
```

##Changelog

    No releases yet

##Contributing

All code contributions must go through a pull request.
Fork the project, create a feature branch, and send me a pull request.
To ensure a consistent code base, you should make sure the code follows
the [coding standards](http://symfony.com/doc/2.0/contributing/code/standards.html).
If you would like to help take a look at the **list of issues**.

##Requirements

    See composer.json for a full list of dependencies.

##Authors

    Jacek Kobus - <kobus.jacek@gmail.com>

## License information

    See the file LICENSE.txt for copying permission.on.