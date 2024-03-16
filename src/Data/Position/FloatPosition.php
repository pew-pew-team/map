<?php

declare(strict_types=1);

namespace PewPew\Map\Data\Position;

use PewPew\Map\Data\PositionInterface;
use PewPew\Map\Data\Size\FloatSize;
use PewPew\Map\Data\SizeInterface;

/**
 * @template-implements PositionInterface<float>
 */
readonly class FloatPosition implements PositionInterface
{
    final public function __construct(
        public float $x = .0,
        public float $y = .0,
    ) {}

    public function add(PositionInterface $position): self
    {
        return new static(
            x: (float) ($this->x + $position->x),
            y: (float) ($this->y + $position->y),
        );
    }

    public function sub(PositionInterface $position): self
    {
        return new static(
            x: (float) ($this->x - $position->x),
            y: (float) ($this->y - $position->y),
        );
    }

    public function toSize(): SizeInterface
    {
        return new FloatSize(
            width: (float) \abs($this->x),
            height: (float) \abs($this->y),
        );
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        /** @var non-empty-string */
        return \vsprintf('Position<float> { x: %g, y: %g }', [
            $this->x,
            $this->y,
        ]);
    }
}
