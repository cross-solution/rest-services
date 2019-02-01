<?php

declare(strict_types=1);

namespace InputFilter\Middleware;

use Psr\Container\ContainerInterface;
use Zend\InputFilter\InputFilterPluginManager;

class InputFilterMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : InputFilterMiddleware
    {
        $config = $container->get('config');
        $map    = $config['input_filter_middleware'] ?? [];

        return new InputFilterMiddleware(
            $container->get(InputFilterPluginManager::class),
            $map
        );
    }
}
