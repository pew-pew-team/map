<?php

declare(strict_types=1);

namespace PewPew\Map;

use PewPew\Map\Data\Layer;
use PewPew\Map\Data\Size;
use PewPew\Map\Data\TileSet;

final readonly class Map
{
    /**
     * @param list<Layer> $layers
     * @param list<TileSet> $tileSets
     * @param Size $size
     */
    public function __construct(
        public array $layers = [],
        public array $tileSets = [],
        public Size $size = new Size(),
    ) {}

    /**
     * @api
     * @param iterable<array-key, TileSet> $tileSets
     */
    public function withTileSets(iterable $tileSets): self
    {
        return new self(
            layers: $this->layers,
            tileSets: $tileSets instanceof \Traversable
                ? \iterator_to_array($tileSets, false)
                : \array_values($tileSets),
            size: $this->size,
        );
    }
}
