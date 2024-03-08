<?php

declare(strict_types=1);

namespace PewPew\Map\Data;

final readonly class Layer
{
    /**
     * @param list<int<0, max>> $tiles
     */
    public function __construct(
        public array $tiles = [],
        public Size $size = new Size(),
        public Position $position = new Position(),
    ) {}

    /**
     * @param int<0, max> $indexId
     */
    public function containsId(int $indexId): bool
    {
        return $this->size->containsId($indexId);
    }

    /**
     * @param int<0, max> $indexId
     */
    public function getX(int $indexId): int
    {
        return $this->size->getX($indexId - $this->position->x);
    }

    /**
     * @param int<0, max> $indexId
     */
    public function getY(int $indexId): int
    {
        return $this->size->getY($indexId - $this->position->y);
    }
}
