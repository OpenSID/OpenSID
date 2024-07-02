<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector;
use Rector\CodeQuality\Rector\Include_\AbsolutizeRequireAndIncludePathRector;
use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromStrictScalarReturnsRector;
use Rector\Php73\Rector\ConstFetch\SensitiveConstantNameRector;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Php73\Rector\FuncCall\JsonThrowOnErrorRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([        
        __DIR__.'/donjo-app/controllers',
        __DIR__.'/donjo-app/core',
        __DIR__.'/donjo-app/helpers',
        __DIR__.'/donjo-app/models',
        __DIR__.'/donjo-app/third_party',
        __DIR__.'/app',
    ]);

    $rectorConfig->phpVersion(PhpVersion::PHP_74);

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
            __DIR__.'/donjo-app/helpers',
        ],
        // return illuminate builder padahal seharusnya return hasOne atau belongsTo dll
        ReturnTypeFromStrictTypedCallRector::class => [
            __DIR__.'/app/Models/',
        ], 
        __DIR__.'/donjo-app/libraries/Log_Viewer.php',
        __DIR__.'/donjo-app/libraries/FeedParser.php',
        __DIR__.'/donjo-app/third_party/DevelBar',
        __DIR__.'/donjo-app/models/migrations',
        __DIR__.'/donjo-app/models/seeders',
        __DIR__.'/app/Libraries/Release.php'
    ]);
    
    // define sets of rules
       $rectorConfig->sets([
            LevelSetList::UP_TO_PHP_74,
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
