<?php

declare(strict_types=1);

namespace PewPew\Map\Tests\Functional\Loader;

use PHPUnit\Framework\Attributes\Group;

#[Group('functional'), Group('pew-pew/map')]
abstract class TiledLoaderTestCase extends LoaderTestCase
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
}
