<?php

declare(strict_types=1);

namespace PewPew\Map\Data\Size;

use PewPew\Map\Data\SizeInterface;

/**
 * @template-implements SizeInterface<float>
 */
readonly class FloatSize implements SizeInterface
{
    public function __construct(
        public float $width = 1.,
        public float $height = 1.,
    ) {}

    /**
     * @api
     */
    public function getArea(): float
    {
        return $this->width * $this->height;
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        /** @var non-empty-string */
        return \vsprintf('Size<float> { width: %g, height: %g }', [
            $this->width,
            $this->height,
        ]);
    }
}
