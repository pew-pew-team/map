<?php

declare(strict_types=1);

namespace PewPew\Map\Loader;

use PewPew\Map\Data\Layer;
use PewPew\Map\Data\Position;
use PewPew\Map\Data\Size;
use PewPew\Map\Data\TileSet;
use PewPew\Map\Data\TilesLayer;
use PewPew\Map\Exception\MapFeatureNotSupportedException;
use PewPew\Map\Loader\Tiled\RenderOrder;
use PewPew\Map\LoaderInterface;
use PewPew\Map\Map;
use Phplrt\Contracts\Source\ReadableInterface;

abstract class TiledLoader implements LoaderInterface
{
    public function load(ReadableInterface $source): ?Map
    {
        try {
            $data = $this->parse($source->getContents());
        } catch (\Throwable) {
            return null;
        }

        if (!$this->isLooksLikeTiledMap($data)) {
            return null;
        }

        return $this->convert($data);
    }

    abstract protected function parse(string $source): array;

    private function isLooksLikeTiledMap(array $data): bool
    {
        return isset($data['tiledversion'])
            && isset($data['type'])
            && $data['type'] === 'map';
    }

    private function getRenderOrder(array $data): RenderOrder
    {
        if (!isset($data['renderorder'])) {
            return RenderOrder::DEFAULT;
        }

        return RenderOrder::tryFrom($data['renderorder'])
            ?? throw new MapFeatureNotSupportedException(\sprintf(
                'Unsupported render order %s',
                \var_export($data['renderorder'], true),
            ));
    }

    private function convert(array $data): ?Map
    {
        // Check compression level
        if (isset($data['compressionlevel']) && $data['compressionlevel'] !== -1) {
            throw new MapFeatureNotSupportedException('Compressed maps is not supported');
        }

        // Check orientation
        if (isset($data['orientation']) && $data['orientation'] !== 'orthogonal') {
            throw new MapFeatureNotSupportedException('Non-orthogonal orientation is not supported');
        }

        // Check infinite flag
        if (isset($data['infinite']) && $data['infinite']) {
            throw new MapFeatureNotSupportedException('Infinite maps is not supported');
        }

        return new Map(
            layers: $this->loadLayersList($data),
            tileSets: $this->loadTileSetsList($data),
            size: new Size(
                width: (int) ($data['width'] ?? 1),
                height: (int) ($data['height'] ?? 1),
            ),
        );
    }

    /**
     * @return list<Layer>
     */
    private function loadLayersList(array $data): array
    {
        $layers = $this->loadLayers($data);

        return \iterator_to_array($layers, false);
    }

    /**
     * @return \Traversable<array-key, Layer>
     */
    private function loadLayers(array $data): \Traversable
    {
        foreach ($data['layers'] ?? [] as $layer) {
            $result = match ($layer['type']) {
                'tilelayer' => $this->loadTilesLayer($layer),
                default => null,
            };

            if ($result !== null) {
                yield $result;
            }
        }
    }

    private function loadTilesLayer(array $layer): TilesLayer
    {
        $size = new Size($layer['width'], $layer['height']);
        $position = new Position($layer['x'], $layer['y']);

        if (isset($layer['encoding'])) {
            throw new MapFeatureNotSupportedException('Encoded layers not supported');
        }

        if (isset($layer['compression'])) {
            throw new MapFeatureNotSupportedException('Compressed layers not supported');
        }

        return new TilesLayer(
            tiles: $layer['data'] ?? [],
            size: $size,
            position: $position,
        );
    }

    /**
     * @return list<TileSet>
     */
    private function loadTileSetsList(array $data): array
    {
        $tileSets = $this->loadTileSets($data);

        return \iterator_to_array($tileSets, false);
    }

    /**
     * @return \Traversable<array-key, TileSet>
     */
    private function loadTileSets(array $data): \Traversable
    {
        foreach ($data['tilesets'] ?? [] as $set) {
            yield $this->loadTileSet($set);
        }
    }

    private function loadTileSet(array $data): TileSet
    {
        if (isset($data['source'])) {
            throw new MapFeatureNotSupportedException(\sprintf(
                'External tilesets is not supported, please include external tileset "%s" inside the map',
                $data['source']
            ));
        }

        return new TileSet(
            pathname: $data['image'],
            tileIdStartsAt: (int) ($data['firstgid'] ?? 1),
            size: new Size(
                width: (int) ($data['imagewidth'] / $data['tilewidth']),
                height: (int) ($data['imageheight'] / $data['tileheight']),
            ),
        );
    }
}
