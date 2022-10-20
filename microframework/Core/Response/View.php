<?php

namespace MicroFramework\Core\Response;

class View extends Response
{
    public function __construct(
        private readonly string $view,
        ?int $statusCode = null,
        ?string $contentType = null)
    {
        parent::__construct(statusCode: $statusCode, contentType: $contentType);
    }

    public function render(?array $args = null, bool $extract = true): int
    {
        return $this->renderWithReference($args, $extract);
    }

    public function renderWithReference(?array &$args = null, bool $extract = true): int
    {
        // TODO: Error checking
        if ($extract && $args)
            extract($args);
        include_once $this->view;
        return 0;
    }
}