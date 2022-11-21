<?php

namespace Framework\API\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
class API
{
    public function __construct(private readonly ?string $appName = null){}

    /**
     * @return string|null
     */
    public function getAppName(): ?string
    {
        return $this->appName;
    }
}