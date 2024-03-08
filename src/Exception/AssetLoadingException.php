<?php

declare(strict_types=1);

namespace PewPew\Map\Exception;

class AssetLoadingException extends \InvalidArgumentException implements TiledExceptionInterface
{
    /**
     * @param non-empty-string $asset
     */
    public function __construct(
        protected readonly string $asset,
        string $message = "",
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @api
     * @return non-empty-string
     */
    public function getAssetPathname(): string
    {
        return $this->asset;
    }
}
