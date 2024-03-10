<?php

declare(strict_types=1);

namespace PewPew\Map\Loader;

final class TiledJsonLoader extends TiledLoader
{
    /**
     * @throws \JsonException
     */
    protected function parse(string $source): array
    {
        return (array) \json_decode(
            json: $source,
            associative: true,
            depth: 32,
            flags: \JSON_THROW_ON_ERROR,
        );
    }
}
