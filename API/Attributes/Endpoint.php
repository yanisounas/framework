<?php

namespace Framework\API\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD|\Attribute::TARGET_FUNCTION)]
class Endpoint
{
    /**
     * @param string $endpointName
     * @param string $description
     * @param string|null $method
     * @param array|null $params
     */
    public function __construct(private readonly string $endpointName, private readonly string $description, private ?string $method = null, private readonly ?array $params = null) {}


    public static function sortByMethod(array $endpoints): array
    {
        $temp = [];

        foreach ($endpoints as $route => $endpoint)
            $temp[$endpoint->getMethod()][$route] = $endpoint;

        return $temp;
    }

    /**
     * @return string
     */
    public function getEndpointName(): string
    {
        return $this->endpointName;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return array|null
     */
    public function getParams(): ?array
    {
        return $this->params;
    }

    /**
     * @return string|null
     */
    public function getMethod(): ?string
    {
        return $this->method;
    }

    /**
     * @param string|null $method
     */
    public function setMethod(?string $method): void
    {
        $this->method = $method;
    }
}