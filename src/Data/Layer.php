<?php

declare(strict_types=1);

namespace PewPew\Map\Data;

readonly class Layer implements \Stringable
{
    public function __construct(
        public Size $size = new Size(),
        public Position $position = new Position(),
    ) {}

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
    public function getPosition(int $indexId): Position
    {
        return new Position(
            x: $this->getX($indexId),
            y: $this->getY($indexId),
        );
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        /** @var non-empty-string */
        return \vsprintf(<<<'TEMPLATE'
            object<%s> {
                size: %s,
                position: %s
            }
            TEMPLATE, [
                (new \ReflectionClass(static::class))
                    ->getShortName(),
                (string) $this->size,
                (string) $this->position,
            ]);
    }
}
