<?php

declare(strict_types=1);

namespace LanguageGuesser;

use LanguageGuesser\InputFilter\InputFilter;
use Zend\ServiceManager\Factory\InvokableFactory;

/**
 * The configuration provider for the LanguageGuesser module
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
            'input_filters' => $this->getInputFilters(),
            'input_filter_middleware' => $this->getInputFilterMiddlewareMap(),
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
                Handler\GuessLanguage::class => Handler\GuessLanguageFactory::class,
            ],
            'delegators' => [
                \Zend\Expressive\Application::class => [
                    RoutesDelegatorFactory::class,
                ],
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
                'language-guesser'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }

    public function getInputFilters()
    {
        return [
            'factories' => [
                InputFilter::class => InvokableFactory::class,
            ],
        ];
    }

    public function getInputFilterMiddlewareMap()
    {
        return [
            RoutesDelegatorFactory::POST_ROUTE_NAME => InputFilter::class
        ];
    }
}
