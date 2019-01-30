<?php
/**
 * CrossServices
 *
 * @filesource
 * @copyright 2019 Cross Solution <http://cross-solution.de>
 */

declare(strict_types=1);

namespace LanguageGuesser;

use InputFilter\Middleware\InputFilterMiddleware;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use LanguageGuesser\Handler\GuessLanguage;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\DelegatorFactoryInterface;

/**
 * ${CARET}
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo   write test
 */
class RoutesDelegatorFactory implements DelegatorFactoryInterface
{
    const POST_ROUTE_NAME = 'language-guesser.post';

    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        /* @var \Zend\Expressive\Application $app */
        $app = $callback();

        $app->post(
            '/guess-lang',
            [
                BodyParamsMiddleware::class,
                InputFilterMiddleware::class,
                GuessLanguage::class,
            ],
            self::POST_ROUTE_NAME
        );

        return $app;
    }

}
