# jQuery DataTables API for CodeIgniter 3

This package is created to handle [server-side](https://www.datatables.net/manual/server-side) works of [DataTables](http://datatables.net) jQuery Plugin via [AJAX option](https://datatables.net/reference/option/ajax) by using Eloquent ORM, Fluent Query Builder or Collection.

```php
return datatables()->of(User::query())->toJson();
return datatables()->of(DB::table('users'))->toJson();
return datatables()->of(User::all())->toJson();

return datatables()->eloquent(User::query())->toJson();
return datatables()->query(DB::table('users'))->toJson();
return datatables()->collection(User::all())->toJson();

return datatables(User::query())->toJson();
return datatables(DB::table('users'))->toJson();
return datatables(User::all())->toJson();
```

## Requirements
- [PHP >= 7.0](http://php.net/)
- [CodeIgniter 3.1.x](https://github.com/bcit-ci/CodeIgniter)
- [jQuery DataTables v1.10.x](http://datatables.net/)

## Documentations

- [Github Docs](https://github.com/yajra/laravel-datatables-docs)
- [Laravel DataTables Quick Starter](https://yajrabox.com/docs/laravel-datatables/master/quick-starter)
- [Laravel DataTables Documentation](https://yajrabox.com/docs/laravel-datatables)
- [Laravel 5.0 - 5.3 Demo Application](https://datatables.yajrabox.com)

## DataTables 8.x Upgrade Guide
There are breaking changes since DataTables v8.x.
If you are upgrading from v7.x to v8.x, please see [upgrade guide](https://yajrabox.com/docs/laravel-datatables/master/upgrade).

## Quick Installation
```bash
$ composer require agungsugiarto/codeigniter-datatabless:"1.x-dev"
```

## Configuration
Copy datatables.php from `vendor/agungsugiarto/codeigniter-datatables/src/config/datatables.php` to folder `application/config/`

And that's it! Start building out some awesome DataTables!

## Debugging Mode
To enable debugging mode, just set ENVIROTMENT to development and the package will include the queries and inputs used when processing the table.


It is advised to use [Homestead](https://laravel.com/docs/5.4/homestead) or [Valet](https://laravel.com/docs/5.4/valet) when working with the package.

## Contributing

Please see [CONTRIBUTING](https://github.com/agungsugiarto/codeigniter-datatables/blob/1.x/.github/CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email [me.agungsugiarto@gmail.com](mailto:me.agungsugiarto@gmail.com) instead of using the issue tracker.

## Credits

- [Arjay Angeles](https://github.com/yajra)
- [Agung Sugiarto](https://github.com/agungsugiarto)
- [bllim/laravel4-datatables-package](https://github.com/bllim/laravel4-datatables-package)
- [All Contributors](https://github.com/yajra/laravel-datatables/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/agungsugiarto/codeigniter-datatables/blob/1.x/LICENSE.md) for more information.
