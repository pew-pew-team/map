<?php

declare(strict_types=1);

namespace PewPew\Map\Tests\Unit;

use PewPew\Map\Data\Size\IntSize;
use PHPUnit\Framework\Attributes\Group;

#[Group('pew-pew/map')]
final class SizeTest extends TestCase
{
    public function testContains(): void
    {
        $size = new IntSize(2, 2);

        self::assertFalse($size->containsId(-1));

        self::assertTrue($size->containsId(0));
        self::assertTrue($size->containsId(1));
        self::assertTrue($size->containsId(2));
        self::assertTrue($size->containsId(3));

        self::assertFalse($size->containsId(4));
    }

    public function testX(): void
    {
        $size = new IntSize(3, 2);

        self::assertSame(0, $size->getX(0));
        self::assertSame(1, $size->getX(1));
        self::assertSame(2, $size->getX(2));

        self::assertSame(0, $size->getX(3));
        self::assertSame(1, $size->getX(4));
        self::assertSame(2, $size->getX(5));
    }

    public function testY(): void
    {
        $size = new IntSize(3, 2);

        self::assertSame(0, $size->getY(0));
        self::assertSame(0, $size->getY(1));
        self::assertSame(0, $size->getY(2));

        self::assertSame(1, $size->getY(3));
        self::assertSame(1, $size->getY(4));
        self::assertSame(1, $size->getY(5));
    }
}
