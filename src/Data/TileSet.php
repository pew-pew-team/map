<?php

declare(strict_types=1);

namespace PewPew\Map\Data;

final readonly class TileSet
{
    /**
     * @psalm-taint-sink file $pathname
     *
     * @param non-empty-string $pathname
     * @param int<1, max> $tileIdStartsAt
     */
    public function __construct(
        public string $pathname,
        public int $tileIdStartsAt,
        public Size $size = new Size(),
    ) {}

    /**
     * @api
     * @param int<1, max> $tileId
     */
    public function containsId(int $tileId): bool
    {
        return $this->size->containsId(\max(0, $tileId - $this->tileIdStartsAt));
    }

    /**
     * @api
     * @param int<1, max> $tileId
     */
    public function getX(int $tileId): int
    {
        return $this->size->getX(\max(0, $tileId - $this->tileIdStartsAt));
    }

    /**
     * @api
     * @param int<1, max> $tileId
     */
    public function getY(int $tileId): int
    {
        return $this->size->getY(\max(0, $tileId - $this->tileIdStartsAt));
    }

    /**
     * @api
     * @psalm-taint-sink file $pathname
     *
     * @param non-empty-string $pathname
     */
    public function withPathname(string $pathname): self
    {
        return new self($pathname, $this->tileIdStartsAt, $this->size);
    }
}
