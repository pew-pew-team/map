<?php

declare(strict_types=1);

namespace PewPew\Map\Internal;

use PewPew\Map\Exception\AssetNotFoundException;
use Phplrt\Contracts\Source\FileInterface;
use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Source\File;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal PewPew\Map\Internal
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
        $this->directories = [...$directories];
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
            if (\is_string($result = \realpath($directory))) {
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

            if (\is_string($result = \realpath($directory))) {
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
            return new File(\realpath($asset) ?: $asset);
        }

        foreach ($this->getAvailableDirectoriesWith($map) as $directory) {
            $pathname = $directory . '/' . $asset;

            if (\is_readable($pathname)) {
                return new File(\realpath($pathname) ?: $pathname);
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
