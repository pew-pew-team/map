<?php

declare(strict_types=1);

namespace PewPew\Map\Tests\Functional\Loader;

use PewPew\Map\LoaderFactory;
use PewPew\Map\LoaderInterface;
use PewPew\Map\Tests\Functional\TestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('functional'), Group('pew-pew/map')]
abstract class LoaderTestCase extends TestCase
{
    abstract protected static function getLoader(): LoaderInterface;

    protected static function getLoaderFactory(): LoaderFactory
    {
        return new LoaderFactory(
            loaders: [
                static::getLoader(),
            ],
            directories: [
                __DIR__ . '/../maps',
            ],
        );
    }
}
