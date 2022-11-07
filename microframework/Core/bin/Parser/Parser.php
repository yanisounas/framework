<?php

namespace MicroFramework\Core\bin\Parser;

class Parser
{
    /**
     * @var callable|null
     */
    private $helpFunction;

    /**
     * @param string|null $appName The name of the application displayed on top of default help command
     * @param string|null $version The version displayed with -v|--version command (if null version commands are disabled by default)
     * @param bool $defaultHelp Let the default help command (true by default)
     * @param callable|null $helpFunction If defaultHelp is false, $helpFunction is required to display help command (if null and defaultHelp false, -h command is disabled)
     * @param array|null $argv Parse a custom array
     */
    public function __construct
    (
        private ?string $appName = null,
        private ?string $version = null,
        private bool $defaultHelp = true,
        ?callable $helpFunction = null,
        private ?array $argv = null
    )
    {
        $this->helpFunction = $helpFunction;
        $this->argv = ($this->argv) ?? $_SERVER["argv"];
    }


    public function addArgument(string $argument, string ...$aliases)
    {
        return new Argument($argument, ...$aliases);
    }


    public function getArgc(): int
    {
        return $this->argc;
    }

    public function getArgv(): array
    {
        return $this->argv;
    }

}