<?php

declare(strict_types=1);

namespace PewPew\Map\Data\Position;

use PewPew\Map\Data\PositionInterface;
use PewPew\Map\Data\Size\IntSize;
use PewPew\Map\Data\SizeInterface;

/**
 * @template-implements PositionInterface<int>
 */
readonly class IntPosition implements PositionInterface
{
    final public function __construct(
        public int $x = 0,
        public int $y = 0,
    ) {}

    public function add(PositionInterface $position): self
    {
        return new static(
            x: (int) ($this->x + $position->x),
            y: (int) ($this->y + $position->y),
        );
    }

    public function sub(PositionInterface $position): self
    {
        return new static(
            x: (int) ($this->x - $position->x),
            y: (int) ($this->y - $position->y),
        );
    }

    public function toSize(): IntSize
    {
        /** @var IntSize & SizeInterface<int> */
        return new IntSize(
            width: \abs($this->x),
            height: \abs($this->y),
        );
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        /** @var non-empty-string */
        return \vsprintf('Position<int> { x: %d, y: %d }', [
            $this->x,
            $this->y,
        ]);
    }
}
