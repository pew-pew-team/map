<?php

declare(strict_types=1);

namespace PewPew\Map\Data\Layer\ObjectsLayer;

use PewPew\Map\Data\PositionInterface;
use PewPew\Map\Data\SizeInterface;

readonly class BoxObject implements \Stringable
{
    public function __construct(
        public SizeInterface $size,
        public PositionInterface $position,
        public float $angle = 0.0,
    ) {}

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        /** @var non-empty-string */
        return \vsprintf(<<<'TEMPLATE'
            Object<Box> {
                position: %s,
                size: %s,
                angle: %g,
            }
            TEMPLATE, [
            (string) $this->position,
            (string) $this->size,
            $this->angle,
        ]);
    }
}
