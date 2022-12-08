<?php

namespace Framework\Router\Attributes;

use Exception;

#[\Attribute(\Attribute::TARGET_METHOD|\Attribute::TARGET_FUNCTION)]
class Route
{
    private array $matches;
    const TYPES = [
        "{i}" => "(\d+)",
        "{s}" => "(\w+)",
    ];

    /**
     * @param string $path
     * @param string $method
     * @param string|null $routeName
     */
    public function __construct(
        private string $path,
        private readonly string $method = "GET",
        private readonly ?string $routeName = null)
    {
        $this->path = ($this->path != '/') ? trim($this->path, '/') : $this->path;
    }

    /**
     * @param string $url
     * @return bool
     * @throws Exception
     */
    public function match(string $url): bool
    {
        $url = ($url != '/') ? trim($url, '/') : $url;

        if (preg_match_all("#{\w+}:\w+#", $this->path, $typedMatches))
        {
            foreach ($typedMatches[0] as $founded)
            {
                preg_match("#{\w+}#", $founded, $type);
                if (isset(self::TYPES[$type[0]]))
                    $this->path = preg_replace("#$founded#", self::TYPES[$type[0]], $this->path);
                else
                    throw new Exception("Unknown type $type[0]");
            }
        }

        $this->path = preg_replace("#:\w+#", "([^/]+)", $this->path);

        if(!preg_match("#^$this->path$#i", $url, $matches))
            return false;

        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    /**
     * @return string
     */
    public function getPath(): string {return $this->path;}
    /**
     * @return string
     */
    public function getMethod(): string {return $this->method;}
    /**
     * @return string|null
     */
    public function getRouteName(): ?string {return $this->routeName;}
    /**
     * @return array
     */
    public function getMatches(): array {return $this->matches;}
}