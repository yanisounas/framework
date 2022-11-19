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
     * @param mixed|null $request
     * @param string|null $routeName
     */
    public function __construct(
        private string $path,
        private readonly string $method = "GET",
        private readonly mixed $request = null,
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

        if (preg_match_all("#({\w+}):\w+#", $this->path, $typedMatches))
        {
            foreach ($typedMatches[0] as $founded)
            {
                preg_match("#{\w+}#", $founded, $type);
                if (isset(self::TYPES[$type[0]]))
                    $this->path = preg_replace("#". $type[0] .":\w+#", self::TYPES[$type[0]], $this->path);
                else
                    throw new Exception("Unknown type $type[0]");
            }
        }


        $path = preg_replace("#:(\w+)#", "([^/]+)", $this->path);
        $reg = "#^$path$#i";




        if(!preg_match($reg, $url, $matches))
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
     * @return mixed|null
     */
    public function getRequest(): mixed {return $this->request;}
    /**
     * @return string|null
     */
    public function getRouteName(): ?string {return $this->routeName;}
    /**
     * @return array
     */
    public function getMatches(): array {return $this->matches;}
}