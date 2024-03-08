<?php

declare(strict_types=1);

namespace PewPew\Map;

use Phplrt\Contracts\Source\ReadableInterface;

interface LoaderInterface
{
    public function load(ReadableInterface $source): ?Map;
}
