<?php

declare(strict_types=1);

namespace PewPew\Map\Data;

final readonly class Size
{
    /**
     * @param int<1, max> $width
     * @param int<1, max> $height
     */
    public function __construct(
        public int $width = 1,
        public int $height = 1,
    ) {}

    /**
     * @return int<1, max>
     */
    public function getArea(): int
    {
        return $this->width * $this->height;
    }

    /**
     * @param int<0, max> $id
     */
    public function containsId(int $id): bool
    {
        return $id >= 0 && $id < $this->getArea();
    }

    /**
     * @param int<0, max> $id
     */
    public function getX(int $id): int
    {
        return $id % $this->width;
    }

    /**
     * @param int<0, max> $id
     */
    public function getY(int $id): int
    {
        return (int) ($id / $this->width);
    }
}
