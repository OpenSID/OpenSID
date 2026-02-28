<?php

declare(strict_types=1);

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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector;
use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodeQuality\Rector\Include_\AbsolutizeRequireAndIncludePathRector;
use Rector\Config\RectorConfig;
use Rector\Php73\Rector\ConstFetch\SensitiveConstantNameRector;
use Rector\Php73\Rector\FuncCall\JsonThrowOnErrorRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromStrictScalarReturnsRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/donjo-app/controllers',
        __DIR__ . '/donjo-app/core',
        __DIR__ . '/donjo-app/helpers',
        __DIR__ . '/donjo-app/models',
        __DIR__ . '/donjo-app/third_party',
        __DIR__ . '/app',
    ]);

    $rectorConfig->phpVersion(PhpVersion::PHP_81);

    // register a single rule, test push
    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);
    $rectorConfig->skip([
        SensitiveConstantNameRector::class,
        ExplicitBoolCompareRector::class,
        JsonThrowOnErrorRector::class,
        LocallyCalledStaticMethodToNonStaticRector::class,
        // skip rule ini, karena menyebabkan error di codeigniter 3
        AbsolutizeRequireAndIncludePathRector::class,
        CompleteDynamicPropertiesRector::class,
        BoolReturnTypeFromStrictScalarReturnsRector::class => [
            __DIR__ . '/donjo-app/helpers',
            __DIR__ . '/app/Providers/ConsoleServiceProvider.php',
        ],
        // return illuminate builder padahal seharusnya return hasOne atau belongsTo dll
        ReturnTypeFromStrictTypedCallRector::class => [
            __DIR__ . '/app/Models/',
            __DIR__ . '/donjo-app/Routes/',
        ],
        __DIR__ . '/donjo-app/libraries/Log_Viewer.php',
        __DIR__ . '/donjo-app/libraries/FeedParser.php',
        __DIR__ . '/donjo-app/libraries/Spreadsheet_Excel_Reader.php',
        __DIR__ . '/donjo-app/third_party/DevelBar',
        __DIR__ . '/donjo-app/models/migrations',
        __DIR__ . '/donjo-app/models/seeders',
        __DIR__ . '/app/Libraries/Release.php',
    ]);

    // define sets of rules
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_81,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::TYPE_DECLARATION,
        SetList::EARLY_RETURN,
    ]);

    // Ensure file system caching is used instead of in-memory.
    $rectorConfig->cacheClass(FileCacheStorage::class);

    // Specify a path that works locally as well as on CI job runners.
    $rectorConfig->cacheDirectory('.cache-rector');

    $rectorConfig->parallel(180, 10, 15);
};
