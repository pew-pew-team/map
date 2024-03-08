<?php

declare(strict_types=1);

namespace PewPew\Map\Internal;

use PewPew\Map\Exception\AssetNotFoundException;
use Phplrt\Contracts\Source\FileInterface;
use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Source\File;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal PewPew\Map
 *
 * @template-implements \IteratorAggregate<array-key, non-empty-string>
 */
final class AssetLoader implements \IteratorAggregate, \Countable
{
    /**
     * @var list<non-empty-string>
     */
    private array $directories = [];

    /**
     * @param iterable<array-key, non-empty-string> $directories
     */
    public function __construct(
        iterable $directories = [],
    ) {
        $this->directories = \is_array($directories)
            ? \array_values($directories)
            : \iterator_to_array($directories, false);
    }

    /**
     * @param non-empty-string $directory
     */
    public function add(string $directory): void
    {
        $this->directories[] = $directory;
    }

    /**
     * @api
     * @return iterable<array-key, non-empty-string>
     */
    public function getAvailableDirectories(): iterable
    {
        foreach ($this->directories as $directory) {
            $result = \realpath($directory);

            if (\is_string($result) && $result !== '') {
                yield $result;
            }
        }
    }

    /**
     * @api
     * @return iterable<array-key, non-empty-string>
     */
    public function getAvailableDirectoriesWith(ReadableInterface $map): iterable
    {
        if ($map instanceof FileInterface) {
            $directory = \dirname($map->getPathname());
            $result = \realpath($directory);

            if (\is_string($result) && $result !== '') {
                yield $result;
            }
        }

        yield from $this->getAvailableDirectories();
    }

    /**
     * @psalm-taint-sink file $asset
     *
     * @param non-empty-string $asset
     */
    public function load(ReadableInterface $map, string $asset): FileInterface
    {
        if (\is_file($asset)) {
            if (($realpath = \realpath($asset)) !== false) {
                $asset = $realpath;
            }

            /** @var non-empty-string $asset */
            return new File($asset);
        }

        foreach ($this->getAvailableDirectoriesWith($map) as $directory) {
            $pathname = $directory . '/' . $asset;

            if (\is_readable($pathname)) {
                if (($realpath = \realpath($pathname)) !== false) {
                    $pathname = $realpath;
                }

                /** @var non-empty-string $pathname */
                return new File($pathname);
            }
        }

        throw new AssetNotFoundException($asset, \sprintf(
            'Asset "%s" not found or could not be loaded',
            $asset,
        ));
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->directories);
    }

    /**
     * @return int<0, max>
     */
    public function count(): int
    {
        return \count($this->directories);
    }
}
