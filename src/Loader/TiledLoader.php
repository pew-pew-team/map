<?php

declare(strict_types=1);

namespace PewPew\Map\Loader;

use PewPew\Map\Data\Layer;
use PewPew\Map\Data\Layer\ObjectsLayer;
use PewPew\Map\Data\Layer\ObjectsLayer\BoxObject;
use PewPew\Map\Data\Layer\TilesLayer;
use PewPew\Map\Data\Position\FloatPosition;
use PewPew\Map\Data\Position\IntPosition;
use PewPew\Map\Data\PositionInterface;
use PewPew\Map\Data\Size\FloatSize;
use PewPew\Map\Data\Size\IntSize;
use PewPew\Map\Data\TileSet;
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
            layers: $this->createLayers($data),
            tileSets: $this->createTileSets($data),
            size: $this->createIntSize($data),
        );
    }

    /**
     * @return list<Layer>
     */
    private function createLayers(array $data, PositionInterface $position = new IntPosition()): array
    {
        $result = [];

        foreach ($data['layers'] ?? [] as $layer) {
            $instances = match ($layer['type']) {
                'tilelayer' => [$this->createTilesLayers($layer, $position)],
                'objectgroup' => [$this->createObjectLayers($layer, $position)],
                'group' => $this->createLayers(
                    data: $layer,
                    position: new FloatPosition(
                        x: (float) ($layer['offsetx'] ?? 0.0),
                        y: (float) ($layer['offsety'] ?? 0.0),
                    ),
                ),
                default => [],
            };


            $result = [...$result, ...$instances];
        }

        return $result;
    }

    private function createObjectLayers(array $layer, PositionInterface $position): ObjectsLayer
    {
        return new ObjectsLayer(
            objects: $this->createObjects($layer['objects'] ?? []),
            position: $this->createFloatPosition($layer)
                ->add($position),
        );
    }

    private function createObjects(array $objects): array
    {
        $result = [];

        foreach ($objects as $object) {
            $result[] = $this->createObject($object);
        }

        return $result;
    }

    /**
     * @param array{
     *     x: int|float,
     *     y: int|float,
     *     width: int|float,
     *     height: int|float,
     *     rotation: int|float,
     *     ...
     * } $object
     */
    private function createObject(array $object): BoxObject
    {
        if (isset($object['polygon'])) {
            return $this->createPolygonObject($object);
        }

        return new BoxObject(
            size: $this->createFloatSize($object),
            position: $this->createFloatPosition($object),
            angle: (float) ($object['rotation'] ?? 0.0),
        );
    }

    /**
     * @param array{
     *     x: int|float,
     *     y: int|float,
     *     width: int|float,
     *     height: int|float,
     *     rotation: int|float,
     *     polygon: list<array{x: int|float, y: int|float}>,
     *     ...
     * } $object
     */
    private function createPolygonObject(array $object): BoxObject
    {
        [$min, $max] = $this->computeAABB($object['polygon'] ?? []);

        return new BoxObject(
            size: $min->sub($max)
                ->toSize(),
            position: $this->createFloatPosition($object),
            angle: (float) ($object['rotation'] ?? 0.0),
        );
    }

    /**
     * @param list<array{x: int|float, y: int|float}> $polygon
     *
     * @return array{PositionInterface, PositionInterface}
     */
    private function computeAABB(array $polygon): array
    {
        $maxX = $maxY = \PHP_INT_MIN;
        $minX = $minY = \PHP_INT_MAX;

        foreach ($polygon as $item) {
            [$maxX, $maxY] = [
                \max($maxX, $item['x']),
                \max($maxY, $item['y']),
            ];

            [$minX, $minY] = [
                \min($minX, $item['x']),
                \min($minY, $item['y']),
            ];
        }

        return [
            new FloatPosition($minX, $minY),
            new FloatPosition($maxX, $maxY),
        ];
    }

    /**
     * @param array{x: int|float, y: int|float, ...} $data
     */
    private function createIntPosition(array $data): IntPosition
    {
        return new IntPosition(
            x: (int) ($data['x'] ?? 0),
            y: (int) ($data['y'] ?? 0),
        );
    }

    /**
     * @param array{x: int|float, y: int|float, ...} $data
     */
    private function createFloatPosition(array $data): FloatPosition
    {
        return new FloatPosition(
            x: (int) ($data['x'] ?? .0),
            y: (int) ($data['y'] ?? .0),
        );
    }

    /**
     * @param array{width: int|float, height: int|float, ...} $data
     */
    private function createIntSize(array $data): IntSize
    {
        return new IntSize(
            width: (int) ($data['width'] ?? 1),
            height: (int) ($data['height'] ?? 1),
        );
    }

    /**
     * @param array{width: int|float, height: int|float, ...} $data
     */
    private function createFloatSize(array $data): FloatSize
    {
        return new FloatSize(
            width: (float) ($data['width'] ?? 1.),
            height: (float) ($data['height'] ?? 1.),
        );
    }

    /**
     * @param array{
     *     x: int|float,
     *     y: int|float,
     *     width: int|float,
     *     height: int|float,
     *     ...
     * } $layer
     */
    private function createTilesLayers(array $layer, PositionInterface $position): TilesLayer
    {
        if (isset($layer['encoding'])) {
            throw new MapFeatureNotSupportedException('Encoded layers not supported');
        }

        if (isset($layer['compression'])) {
            throw new MapFeatureNotSupportedException('Compressed layers not supported');
        }

        return new TilesLayer(
            tiles: $layer['data'] ?? [],
            size: $this->createIntSize($layer),
            position: $this->createFloatPosition($layer)
                ->add($position),
        );
    }

    /**
     * @return list<TileSet>
     */
    private function createTileSets(array $data): array
    {
        $result = [];

        foreach ($data['tilesets'] ?? [] as $set) {
            $result[] = $this->createTileSet($set);
        }

        return $result;
    }

    private function createTileSet(array $data): TileSet
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
            size: new IntSize(
                width: (int) ($data['imagewidth'] / $data['tilewidth']),
                height: (int) ($data['imageheight'] / $data['tileheight']),
            ),
        );
    }
}
