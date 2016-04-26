# A simple php Discogs Api.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jolitagrazyte/discogs-api-wrapper.svg?style=flat-square)](https://packagist.org/packages/jolitagrazyte/discogs-api-wrapper)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/JolitaGrazyte/discogs-api-wrapper/master.svg?style=flat-square)](https://travis-ci.org/JolitaGrazyte/discogs-api-wrapper)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/4d8c7480-2fa7-4333-b6f7-80f2f99f5e44.svg?style=flat-square)](https://insight.sensiolabs.com/projects/4d8c7480-2fa7-4333-b6f7-80f2f99f5e44)
[![Quality Score](https://img.shields.io/scrutinizer/g/JolitaGrazyte/discogs-api-wrapper.svg?style=flat-square)](https://scrutinizer-ci.com/g/JolitaGrazyte/discogs-api-wrapper)
[![Total Downloads](https://img.shields.io/packagist/dt/jolitagrazyte/discogs-api-wrapper.svg?style=flat-square)](https://packagist.org/packages/jolitagrazyte/discogs-api-wrapper)


This package makes it easy to communicate with discogs-api. It uses [Guzzle 6](https://github.com/guzzle/guzzle) and is very simple to install and to use.

You can use it with any PHP framework or just simply in your naked PHP application.

However if you want to use it in Laravel framework, i'd suggest to also install [jolitagrazyte/laravel-discogs package](https://github.com/JolitaGrazyte/laravel-discogs), 
which provides a facade for even an easier way to play with it.

## Installation

You can install the package via composer:

``` bash
composer require jolitagrazyte/discogs-api
```

## Usage

### Endpoints with no authentication required

For the endpoints, where token is not required, first argument can be easily left empty.
``` php
$discogs = new DiscogsApi();

//get artist with id 1
$artist = $discogs->artist('1');

//get releases of the artist with id 1
$artistReleases = $discogs->artistReleases('1');

//get label with id 1 
$label = $discogs->label('1');

//get releases of the label with id 1
$labelReleases = $discogs->labelReleases('1');

//get release with id 1
$releases = $discogs->releases('1');

//get master release with id 1
$masterRelease = $discogs->masterRelease('1');
```

### Endpoints where authentication is required
For the endpoints, where token is required, you must add your discogs-token.

You can obtain it at https://www.discogs.com/settings/developers.

#### Orders
To get your orders, you can use getMyOrders()-method.

Orders list comes paginated with a default - 50 orders per page, but it can be changed. 

If you need, you can add some optional parameters: `page`, `perPage` (with a max of 100), order status as  `status`, `sort` and `sortOrder`.

If you need more information about the parameters you can read about it at https://www.discogs.com/developers.

```php
$discogs = new DiscogsApi('discogs-token', 'app-name');

//get orders
$orders = $discogs->getMyOrders();

//get orders with parameters
$ordersWithOptions = $discogs->getMyOrders(3, 25, "created", "desc");
```

For getting one specific order by id:
``` php
$discogs = new DiscogsApi('discogs-token', 'app-name');

//get order with id
$order = $discogs->orderWithId('123');
```

It is also possible to retrieve the messages of an order:
```php
$discogs = new DiscogsApi('discogs-token', 'app-name');

//get messages  of an order with id
$ordersMessages = $discogs->orderMessages('123');
```

For changing the status of the order or adding the shipping to the order.
```php
$discogs = new DiscogsApi('discogs-token', 'app-name');

//change order status to 'Shipped'
$orders = $discogs->changeOrderStatus('123', 'Shipped');

//add shipping to an order with id
$order = $discogs->addShipping('123', '12.60');
```


#### Search

If you want to add some extra search parameters you can do it by 
first creating a SearchParameters object and then chaining as many options as you want.
  
```php
$discogs = new DiscogsApi('discogs-token', 'app-name');

// create a SearchParameters object and chain some search paramater
$searchParameters = SearchParameters::make()->format('LP')->year('1996');

//do a search request with query = 'MoWax' and passing the SearchParameters object
$searchResult = $discogs->search('MoWax', $searchParameters);
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email hello@jolitagrazyte.com instead of using the issue tracker.

## Credits

- [Jolita Grazyte](https://github.com/JolitaGrazyte)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
