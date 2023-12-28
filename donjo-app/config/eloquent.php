<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Pagination\Cursor;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Facade;

Carbon::setLocale('id');
CarbonImmutable::setLocale('id');
CarbonPeriod::setLocale('id');
CarbonInterval::setLocale('id');

$capsule = new Capsule();
$capsule->addConnection([
    'driver'    => $db['default']['dbdriver'] == 'mysqli' ? 'mysql' : $db['default']['dbdriver'],
    'host'      => $db['default']['hostname'],
    'port'      => $db['default']['port'],
    'database'  => $db['default']['database'],
    'username'  => $db['default']['username'],
    'password'  => $db['default']['password'],
    'charset'   => $db['default']['char_set'],
    'collation' => $db['default']['dbcollat'],
    'prefix'    => $db['default']['dbprefix'],
    'stricton'  => $db['default']['stricton'],
    'options'   => [
        \PDO::ATTR_EMULATE_PREPARES => true,
    ],
]);

Container::setInstance($capsule->getContainer());

$capsule->getContainer()->singleton('events', static function () use ($capsule) {
    return new Dispatcher($capsule->getContainer());
});

$capsule->getContainer()->singleton('db', static function () use ($capsule) {
    return $capsule->getDatabaseManager();
});

$capsule->setAsGlobal();
$capsule->setEventDispatcher($capsule->getContainer()->get('events'));
$capsule->bootEloquent();

Facade::clearResolvedInstances();
Facade::setFacadeApplication($capsule->getContainer());

Paginator::$defaultView       = 'admin/layouts/components/pagination_default';
Paginator::$defaultSimpleView = 'admin/layouts/components/pagination_simple_default';

Paginator::viewFactoryResolver(static function () {
    return view();
});

Paginator::currentPathResolver(static function () {
    return current_url();
});

Paginator::currentPageResolver(static function ($pageName = 'page') {
    $page = get_instance()->input->get($pageName);

    if (filter_var($page, FILTER_VALIDATE_INT) !== false && (int) $page >= 1) {
        return (int) $page;
    }

    return 1;
});

Paginator::queryStringResolver(static function () {
    return get_instance()->uri->uri_string();
});

CursorPaginator::currentCursorResolver(static function ($cursorName = 'cursor') {
    return Cursor::fromEncoded(get_instance()->input->get($cursorName));
});

\Illuminate\Database\Query\Builder::macro('toRawSql', function () {
    return array_reduce($this->getBindings(), static function ($sql, $binding) {
        return preg_replace('/\?/', is_numeric($binding) ? $binding : "'{$binding}'", $sql, 1);
    }, $this->toSql());
});

\Illuminate\Database\Eloquent\Builder::macro('toRawSql', function () {
    return $this->getQuery()->toRawSql();
});

/**
 * Uncomment untuk listen semua query dari laravel database.
 */
// \Illuminate\Support\Facades\Event::listen(\Illuminate\Database\Events\QueryExecuted::class, function ($query) {
//     log_message('notice', $query->time . ' | ' . array_reduce($query->bindings, static function ($sql, $binding) {
//         return preg_replace('/\?/', is_numeric($binding) ? $binding : "'{$binding}'", $sql, 1);
//     }, $query->sql));
// });
