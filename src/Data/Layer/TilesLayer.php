<?php

declare(strict_types=1);

namespace PewPew\Map\Data\Layer;

use PewPew\Map\Data\Layer;
use PewPew\Map\Data\Position\IntPosition;
use PewPew\Map\Data\PositionInterface;
use PewPew\Map\Data\Size\IntSize;

readonly class TilesLayer extends Layer
{
    /**
     * @param list<int<0, max>> $tiles
     */
    public function __construct(
        public array $tiles = [],
        public IntSize $size = new IntSize(),
        PositionInterface $position = new IntPosition(),
    ) {
        parent::__construct($position);
    }

    /**
     * @api
     * @param int<0, max> $indexId
     */
    public function containsId(int $indexId): bool
    {
        return $this->size->containsId($indexId);
    }

    /**
     * @api
     * @param int<0, max> $indexId
     * @return int<0, max>
     */
    public function getX(int $indexId): int
    {
        return $this->size->getX(\max(0, $indexId - $this->position->x));
    }

    /**
     * @api
     * @param int<0, max> $indexId
     * @return int<0, max>
     */
    public function getY(int $indexId): int
    {
        return $this->size->getY(\max(0, $indexId - $this->position->y));
    }

    /**
     * @api
     * @param int<0, max> $indexId
     */
    public function getPosition(int $indexId): IntPosition
    {
        return new IntPosition(
            x: $this->getX($indexId),
            y: $this->getY($indexId),
        );
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        $slice = \array_slice($this->tiles, 0, 5);

        /** @var non-empty-string */
        return \sprintf(<<<'TEMPLATE'
            Layer<Tiles> {
                tiles(%d): [ %s ],
                size: %s,
                position: %s
            }
            TEMPLATE,
            \count($this->tiles),
            \implode(', ', $slice) . ($this->tiles > 5 ? ' ...' : ''),
            $this->size,
            $this->position,
        );
    }
}
