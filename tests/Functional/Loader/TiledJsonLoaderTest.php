<?php

declare(strict_types=1);

namespace PewPew\Map\Tests\Functional\Loader;

use PewPew\Map\Data\Layer\TilesLayer;
use PewPew\Map\Data\Position\FloatPosition;
use PewPew\Map\Data\Position\IntPosition;
use PewPew\Map\Data\Size\IntSize;
use PewPew\Map\Loader\TiledJsonLoader;
use PewPew\Map\LoaderFactory;
use PewPew\Map\Tests\Functional\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

#[Group('functional'), Group('pew-pew/map')]
final class TiledJsonLoaderTest extends TestCase
{
    /**
     * @var list<int<0, max>>
     */
    public const array DEFAULT_TILES = [
        1, 0, 2, 0,
        0, 3, 0, 4,
        2, 0, 1, 0,
        0, 4, 0, 3,
    ];

    private static function getLoader(): LoaderFactory
    {
        return new LoaderFactory(
            loaders: [
                new TiledJsonLoader(),
            ],
            directories: [
                __DIR__ . '/../maps',
            ],
        );
    }

    public static function differentRenderOrderMapsDataProvider(): iterable
    {
        $instance = self::getLoader();

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

            self::assertEquals(new IntSize(4, 4), $layer->size);
            self::assertEquals(new FloatPosition(), $layer->position);

            self::assertSame(self::DEFAULT_TILES, $layer->tiles);
        }
    }

    public function testObjectLayersLoading(): void
    {
        $loader = self::getLoader();

        $map = $loader->load(new \SplFileInfo(__DIR__ . '/../maps/object-layers.tmj'));

        self::assertCount(3, $map->layers);
    }
}
