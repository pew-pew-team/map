<?php

declare(strict_types=1);

namespace PewPew\Map\Tests\Functional\Loader;

use PewPew\Map\Data\Position;
use PewPew\Map\Data\Size;
use PewPew\Map\Data\TilesLayer;
use PewPew\Map\Loader\TiledJsonLoader;
use PewPew\Map\LoaderFactory;
use PewPew\Map\LoaderInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

#[Group('functional'), Group('pew-pew/map')]
final class TiledJsonLoaderTest extends TiledLoaderTestCase
{
    protected static function getLoader(): LoaderInterface
    {
        return new TiledJsonLoader();
    }

    public static function differentRenderOrderMapsDataProvider(): iterable
    {
        $instance = self::getLoaderFactory();

        foreach ([
            __DIR__ . '/../maps/left-down.tmj',
            __DIR__ . '/../maps/left-up.tmj',
            __DIR__ . '/../maps/right-down.tmj',
            __DIR__ . '/../maps/right-up.tmj',
        ] as $pathname) {
            yield \basename($pathname) => [$instance, $pathname];
        }
    }

    #[DataProvider('differentRenderOrderMapsDataProvider')]
    public function testMapLoad(LoaderFactory $loader, string $pathname): void
    {
        $map = $loader->load(new \SplFileInfo($pathname));

        /** @var TilesLayer $layer */
        foreach ($map->layers as $layer) {
            self::assertInstanceOf(TilesLayer::class, $layer);

            self::assertEquals(new Size(4, 4), $layer->size);
            self::assertEquals(new Position(), $layer->position);

            self::assertSame(self::DEFAULT_TILES, $layer->tiles);
        }
    }
}
