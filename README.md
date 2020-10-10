# Bootstrap Paginator

[By Sébastien L'haire](http://sebastien.lhaire.org)

A Laravel library to generate paginations with [Bootstrap](https://getbootstrap.com/) 4 CSS Framework.

This library provides two different pagnators:

* a classical paginator with page numbers and previous and next button.
* an alphabetical paginator with letters

Both paginators can be used in same page.

# Installation

1. `composer require seblhaire/bootstrappaginator`

2. Optionally install Boostrap using `npm install bootstrap`

3. (optionally) add in `config/app.php`
```php
  'providers' => [
    ...
      Seblhaire\BootstrapPaginator\BootstrapPaginatorServiceProvider::class,
      ...
    ],
    'aliases' => [
        ...
        'BootstrapPaginator' => Seblhaire\BootstrapPaginator\BootstrapPaginator::class,
      ]
```
4. Publish package (optionally).
``` sh
$ php artisan vendor:publish
```

5. Add a translation to Laravel existing `pagination.php` translation file in directory
`resources/lang/en/`. Simply add key:

```
'all' => 'All'
```

# Usage

Declare Facade in Controller headers:

```
use Seblhaire\BootstrapPaginator\BootstrapPaginator;
```
