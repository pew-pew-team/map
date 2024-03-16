<?php

declare(strict_types=1);

namespace PewPew\Map\Data;

use PewPew\Map\Data\Position\IntPosition;

readonly class Layer implements \Stringable
{
    public function __construct(
        public PositionInterface $position = new IntPosition(),
    ) {}

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        /** @var non-empty-string */
        return \vsprintf(<<<'TEMPLATE'
            Layer<%s> {
                position: %s,
            }
            TEMPLATE, [
                (new \ReflectionClass(static::class))
                    ->getShortName(),
                (string) $this->position,
            ]);
    }
}
