<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;
use Rector\Php84\Rector\Param\ExplicitNullableParamTypeRector;

return RectorConfig::configure()
    ->withCache(cacheDirectory: __DIR__ . '/../cache/rector')
    ->withPaths([
        __DIR__ . '/../../src',
        __DIR__ . '/../../bin/console',
        __DIR__ . '/../../config',
        __DIR__ . '/../../public/index.php',
        __DIR__ . '/../php-cs-fixer/.php-cs-fixer.php',
        __DIR__ . '/rector.php',
        __DIR__ . '/rector-tests.php',
        __DIR__ . '/../../templates',
    ])
    ->withPhpSets(php83: true)
    ->withRules([
        DeclareStrictTypesRector::class,
        ExplicitNullableParamTypeRector::class,
    ])
    ;
