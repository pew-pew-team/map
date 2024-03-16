<?php

declare(strict_types=1);

namespace PewPew\Map\Data\Layer;

use PewPew\Map\Data\Layer;
use PewPew\Map\Data\Position\IntPosition;
use PewPew\Map\Data\PositionInterface;
use PewPew\Map\Internal\Formatter;

readonly class ObjectsLayer extends Layer
{
    public function __construct(
        public array $objects = [],
        PositionInterface $position = new IntPosition(),
    ) {
        parent::__construct($position);
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        $objects = [];
        foreach ($this->objects as $object) {
            $objects[] = (string) $object;
        }

        /** @var non-empty-string */
        return \vsprintf(<<<'TEMPLATE'
            Layer<Objects> {
                objects(%d): [
                    %s,
                ],
                position: %s,
            }
            TEMPLATE, [
            \count($this->objects),
            Formatter::indentAllBody(
                content: \implode(",\n", $objects),
                value: 2,
            ),
            (string) $this->position,
        ]);
    }
}
