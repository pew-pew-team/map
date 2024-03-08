<?php

declare(strict_types=1);

namespace PewPew\Map\Data;

readonly class TilesLayer extends Layer
{
    /**
     * @param list<int<0, max>> $tiles
     */
    public function __construct(
        public array $tiles = [],
        Size $size = new Size(),
        Position $position = new Position(),
    ) {
        parent::__construct($size, $position);
    }
}
