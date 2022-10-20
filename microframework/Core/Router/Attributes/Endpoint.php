<?php

namespace MicroFramework\Core\Router\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD|\Attribute::TARGET_FUNCTION)]
class Endpoint extends Route
{
    /**
     * @param string $path
     * @param string $method
     * @param mixed|null $request
     * @param string|null $routeName
     * @param string|null $description
     */
    public function __construct(
        string $path,
        string $method = "GET",
        mixed $request = null,
        ?string $routeName = null,
        private readonly ?string $description = null)
    {
        parent::__construct($path, $method, $request, $routeName);
    }

    /**
     * Get Endpoint Description
     *
     * @return null|string
     */
    public function getDescription(): ?string {return $this->description;}


}