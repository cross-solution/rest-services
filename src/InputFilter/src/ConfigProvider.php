<?php

declare(strict_types=1);

namespace InputFilter;

use InputFilter\Middleware\InputFilterMiddleware;
use InputFilter\Middleware\InputFilterMiddlewareFactory;
use Zend\ServiceManager\Factory\InvokableFactory;

/**
 * The configuration provider for the InputFilter module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'invokables' => [
            ],
            'factories'  => [
                InputFilterMiddleware::class => InputFilterMiddlewareFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                'input-filter'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}
