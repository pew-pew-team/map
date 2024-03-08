<?php

declare(strict_types=1);

namespace PewPew\Map\Data;

readonly class Size implements \Stringable
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
     * @api
     * @return int<1, max>
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
    public function getPosition(int $id): Position
    {
        return new Position(
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
        return \vsprintf('object<Size> { width: %d, height: %d }', [
            $this->width,
            $this->height,
        ]);
    }
}
