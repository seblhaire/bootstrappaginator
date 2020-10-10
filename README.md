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
'all' => 'All'h
```


# Usage

Declare Facade in Controller headers:

```
 use Seblhaire\BootstrapPaginator\BootstrapPaginator;
```

Your route must contain a parameter for page in last segment:

```
Route::get('/issues/{page?}', 'MainController@issues')->name('issues');
```

Eg: you can have a route `https://test.site/issues/9`, route `https://test.site/issues` should display page 1.  Controller method can be declared like this:

```
function issues($page = 1){ ...}
```

If you include paginator alpha, last segment will be an initial:

```
Route::get('/authors/{initial?}', 'MainController@authors')->name('authors');
```
Eg: you can have a route `https://test.site/authors/D` would display all authors beginning with D. `https://test.site/authors` will display all items. Controller method can be declared like this:

```
public function authors($initial = config('bootstrappaginator.valueforall')){ ... }
```

You can combine both paginators in a single page. In this case, initial will be in the before last position and page parameter in the last position:

```
Route::get('/authors/{initial?}/{page?}', 'MainController@authors')->name('authors');
```

In this case `https://test.site/authors` displays first page of all items. `https://test.site/authors/all/3` will display third page of all items. `https://test.site/authors/D` will display first page of items beginning with D. And `https://test.site/authors/D/3` will display third page of items beginning with D. Controller method can be declared like this:

```
public function authors($initial = config('bootstrappaginator.valueforall'), $page =1){ ... }
```

Then the variables initiated in method arguments can be used in method content and passed in blade view: 

```
$route = 'authors';
$options = ['nbpages' => 4, 'params' => ['initial' => $initial]];
$optionalpha = ['type' => 'alpha'];
$paginator = BootstrapPaginator::init($page, $route, $options);
$paginatoralpha = BootstrapPaginator::init($initiale, $route, $optionalpha);
```

In this example, numeric paginator will contain current initial in the link urls. Alpha paginator's links will direct web page users to the first page with the initial they contain. Then in the view, print your paginator like this:

`{!! $paginator->render() !!}`

or simply

`{!! $paginator !!}`

## Parameters

* `$page` : current page number or initial letter;
* `$route`: current route id;
* `$options` : array of values to replace default values in config file:
  * `type`: either `numeric` or `alpha`;
  * `params` : default `[]`. Array of parameters used by `route()` helper to issue urls in paginator. See above examples;
  * `getparams` : default `[]`. GET parameters that will be added to path. Eg: `['search' => 'dummy', 'type' => 'global']` will be translated into `url?search=dummy&type=global`
  * numeric paginator specific parameters:
      * `pageparam` : default `page`. Id of page parameter in route.
      * `nbpages`:  Default 1. Number of pages that will be displayed.
      * `withoutdots`: default `false`. Display all pages without spacing ranges by dots;
      * `max_pages_without_dots`: default `9`. Maximum number of pages without dot separation. 
      * `items_before_after_current`: default `2`. Number of pages to display before and after page item.
  * alpha paginator specific parameters:
     * `initialparam`: default `initial`. Id of initial parameter in route;
     * `valueforall` : delault `all`. Value used to display all items instead of items beginnig with a certain parameter.
  * `class`: default `pagination`. Class of `<ul>` surrounding pagination.
  * `itemclass`: default `page-item`. Class of `<li>` element.
  * `linkclass`: default `page-link`. Class of `<a>` element.
  * `activelink`: default ` active`. Class added to current element.
  * `disabledlink`: default ` disabled`. Class of disabled button.
  * `srcurrent` default: ` <span class="sr-only">(current)</span>`- Element added to current element for speech aid tools.
  * `previousbuttoncontent`: default `<span aria-hidden=\"true\">&laquo;</span>`. Content for previous button.
  * `nextbuttoncontent`:  default `<span aria-hidden=\"true\">&raquo;</span>`. Content for next button.

## Config files

Accessible through

```php
config('bootstrappaginator')
```

