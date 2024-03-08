<?php

declare(strict_types=1);

namespace PewPew\Map;

use PewPew\Map\Internal\AssetLoader;
use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Contracts\Source\SourceFactoryInterface;
use Phplrt\Source\SourceFactory;

final class LoaderFactory implements LoaderInterface
{
    public readonly AssetLoader $assets;

    /**
     * @param LoaderInterface $loaders
     * @param iterable<array-key, non-empty-string> $directories
     */
    public function __construct(
        private array $loaders = [],
        iterable $directories = [],
        private readonly SourceFactoryInterface $sources = new SourceFactory(),
    ) {
        $this->assets = new AssetLoader($directories);
    }

    public function add(LoaderInterface $loader): void
    {
        $this->loaders[] = $loader;
    }

    public function load(mixed $source): Map
    {
        $source = $this->sources->create($source);

        foreach ($this->loaders as $loader) {
            $map = $loader->load($source);

            if ($map !== null) {
                return $this->resolveAssets($source, $map);
            }
        }

        throw new \InvalidArgumentException('Unsupported map format');
    }

    private function resolveAssets(ReadableInterface $source, Map $map): Map
    {
        $tileSets = [];

        foreach ($map->tileSets as $tileSet) {
            $asset = $this->assets->load($source, $tileSet->pathname);

            $tileSets[] = $tileSet->withPathname($asset->getPathname());
        }

        return $map->withTileSets($tileSets);
    }
}
