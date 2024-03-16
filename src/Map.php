<?php

declare(strict_types=1);

namespace PewPew\Map;

use PewPew\Map\Data\Layer;
use PewPew\Map\Data\Size\IntSize;
use PewPew\Map\Data\TileSet;
use PewPew\Map\Internal\Formatter;

readonly class Map implements \Stringable
{
    /**
     * @param list<Layer> $layers
     * @param list<TileSet> $tileSets
     * @param IntSize $size
     */
    public function __construct(
        public array $layers = [],
        public array $tileSets = [],
        public IntSize $size = new IntSize(),
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

    public function __toString(): string
    {
        $layers = $tileSets = [];

        foreach ($this->layers as $layer) {
            $layers[] = (string) $layer;
        }

        foreach ($this->tileSets as $tileSet) {
            $tileSets[] = (string) $tileSet;
        }

        return \sprintf(<<<'TPL'
            Map {
                layers(%d): [
                    %s,
                ],
                tileSets(%d): [
                    %s,
                ],
                size: %s,
            }
            TPL,
            \count($this->layers),
            Formatter::indentAllBody(
                content: \implode(",\n", $layers),
                value: 2,
            ),
            \count($this->tileSets),
            Formatter::indentAllBody(
                content: \implode(",\n", $tileSets),
                value: 2,
            ),
            Formatter::indentAllBody(
                content: (string) $this->size,
                value: 2,
            ),
        );
    }
}
