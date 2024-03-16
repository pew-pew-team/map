<?php

declare(strict_types=1);

namespace PewPew\Map\Tests\Unit;

use PewPew\Map\Data\Size\IntSize;
use PewPew\Map\Data\TileSet;
use PHPUnit\Framework\Attributes\Group;

#[Group('pew-pew/map')]
final class TileSetTest extends TestCase
{
    public function testContains(): void
    {
        $set = new TileSet('', $offset = 0xDEAD_BEEF, new IntSize(
            width: 2,
            height: 2,
        ));

        self::assertFalse($set->containsId(-1 + $offset));

        self::assertTrue($set->containsId(0 + $offset));
        self::assertTrue($set->containsId(1 + $offset));
        self::assertTrue($set->containsId(2 + $offset));
        self::assertTrue($set->containsId(3 + $offset));

        self::assertFalse($set->containsId(4 + $offset));
    }

    public function testX(): void
    {
        $set = new TileSet('', $offset = 0xDEAD_BEEF, new IntSize(
            width: 3,
            height: 2,
        ));

        self::assertSame(0, $set->getX(0 + $offset));
        self::assertSame(1, $set->getX(1 + $offset));
        self::assertSame(2, $set->getX(2 + $offset));

        self::assertSame(0, $set->getX(3 + $offset));
        self::assertSame(1, $set->getX(4 + $offset));
        self::assertSame(2, $set->getX(5 + $offset));
    }

    public function testY(): void
    {
        $set = new TileSet('', $offset = 0xDEAD_BEEF, new IntSize(
            width: 3,
            height: 2,
        ));

        self::assertSame(0, $set->getY(0 + $offset));
        self::assertSame(0, $set->getY(1 + $offset));
        self::assertSame(0, $set->getY(2 + $offset));

        self::assertSame(1, $set->getY(3 + $offset));
        self::assertSame(1, $set->getY(4 + $offset));
        self::assertSame(1, $set->getY(5 + $offset));
    }
}
