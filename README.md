# Laravel Simplestats

Simple statistics package for Laravel inspired by [Simplestats for Kohana](https://github.com/creatoro/simplestats).

## Requirements

* PHP 7.1+
* Laravel 5.5+

## Installation

Install the package via composer:
````
composer require czemu/laravel-simplestats
````

If you're using Laravel >= 5.5, the package will register itself automatically.

Publish the migrations:
````
php artisan vendor:publish --provider='Czemu\Simplestats\SimplestatsServiceProvider' --tag="migrations"
````

Run the migrations:
````
php artisan migrate
````

You can also publish the config file:
````
php artisan vendor:publish --provider='Czemu\Simplestats\SimplestatsServiceProvider' --tag="config"
````

## Usage

Update or create statistics for an item named "file" with id "1":
````php
Simplestats::update('file', 1);
````

Update or create unique statistics (based on cookie) for an item named "print" with id "2":
````php
Simplestats::update('print', 2, TRUE);
````

Get statistics for an item named "page" with id "3":
````php
Simplestats::get('page', 3);
````

Get statistics for an item named "download" with id "4" on specified date:
````php
Simplestats::get('download', 4, '2018-05-20');
````

Get statistics for an item named "click" with id "5" between specified date range:
````php
Simplestats::get('download', 5, ['2018-05-01', '2018-05-15']);
````

## License

The MIT License (MIT). Please see the [LICENSE.md](LICENSE.md) file for more information.
