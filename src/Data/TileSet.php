<?php

declare(strict_types=1);

namespace PewPew\Map\Data;

final readonly class TileSet implements \Stringable
{
    /**
     * @psalm-taint-sink file $pathname
     *
     * @param non-empty-string $pathname
     * @param int<1, max> $tileIdStartsAt
     */
    public function __construct(
        public string $pathname,
        public int $tileIdStartsAt = 1,
        public Size $size = new Size(),
    ) {}

    /**
     * @api
     * @param int<1, max> $tileId
     */
    public function containsId(int $tileId): bool
    {
        return $tileId >= 1 && $this->size->containsId(
            id: \max(0, $tileId - $this->tileIdStartsAt),
        );
    }

    /**
     * @api
     * @param int<1, max> $tileId
     * @return int<0, max>
     */
    public function getX(int $tileId): int
    {
        return $this->size->getX(\max(0, $tileId - $this->tileIdStartsAt));
    }

    /**
     * @api
     * @param int<1, max> $tileId
     * @return int<0, max>
     */
    public function getY(int $tileId): int
    {
        return $this->size->getY(\max(0, $tileId - $this->tileIdStartsAt));
    }

    /**
     * @api
     * @param int<1, max> $tileId
     */
    public function getPosition(int $tileId): Position
    {
        return new Position(
            x: $this->getX($tileId),
            y: $this->getY($tileId),
        );
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

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        /** @var non-empty-string */
        return \vsprintf(<<<'TEMPLATE'
            object<TileSet = %s> {
                id: %d,
                size: %s,
            }
            TEMPLATE, [
                \var_export($this->pathname, true),
                $this->tileIdStartsAt,
                (string) $this->size,
            ]);
    }
}
