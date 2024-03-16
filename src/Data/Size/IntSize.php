<?php

declare(strict_types=1);

namespace PewPew\Map\Data\Size;

use PewPew\Map\Data\Position\IntPosition;
use PewPew\Map\Data\SizeInterface;

/**
 * @template-implements SizeInterface<int<0, max>>
 */
readonly class IntSize implements SizeInterface
{
    /**
     * @param int<0, max> $width
     * @param int<0, max> $height
     */
    public function __construct(
        public int $width = 1,
        public int $height = 1,
    ) {}

    /**
     * @api
     * @return int<0, max>
     */
    public function getArea(): int
    {
        return $this->width * $this->height;
    }

    /**
     * @api
     * @param int<0, max> $id
     */
    public function containsId(int $id): bool
    {
        return $id >= 0 && $id < $this->getArea();
    }

    /**
     * @api
     * @param int<0, max> $id
     * @return int<0, max>
     */
    public function getX(int $id): int
    {
        /** @var int<0, max> */
        return $id % $this->width;
    }

    /**
     * @api
     * @param int<0, max> $id
     * @return int<0, max>
     */
    public function getY(int $id): int
    {
        /** @var int<0, max> */
        return (int) ($id / $this->width);
    }

    /**
     * @api
     * @param int<0, max> $id
     */
    public function getPosition(int $id): IntPosition
    {
        return new IntPosition(
            x: $this->getX($id),
            y: $this->getY($id),
        );
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        /** @var non-empty-string */
        return \vsprintf('Size<int> { width: %d, height: %d }', [
            $this->width,
            $this->height,
        ]);
    }
}
