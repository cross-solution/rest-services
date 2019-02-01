<?php

declare(strict_types=1);

namespace InputFilter\Middleware;

use InputFilter\ResponseStrategy\DefaultResponseStrategy;
use InputFilter\ResponseStrategy\ResponseStrategyInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Router\RouteResult;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterPluginManager;

class InputFilterMiddleware implements MiddlewareInterface
{
    private $inputFilters;
    private $map;

    public function __construct(InputFilterPluginManager $inputFilters, array $map = [])
    {
        $this->inputFilters = $inputFilters;
        $this->map = $map;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $routeName = $request->getAttribute(RouteResult::class)->getMatchedRouteName();
        $filter    = $this->loadInputFilter($routeName);
        $data      = $this->extractData($request);

        $filter->setData($data);

        if ($filter->isValid()) {
            return $handler->handle(
                $request
                    ->withParsedBody($filter->getValues())
                    ->withAttribute(self::class, $filter)
            );
        }

        // Handle invalid

        $strategy = $this->getResponseStrategy($routeName);
        $response = $strategy($request, $filter);

        return $response;
    }

    private function extractData(ServerRequestInterface $request) : array
    {
        switch ($request->getMethod()) {
            case 'GET':
                return $request->getQueryParams();

            case 'POST':
                return $request->getParsedBody();

            default:
                throw new \RuntimeException('Unsupported request method: ' . $request->getMethod(), 500);
        }
    }

    private function loadInputFilter($name) : InputFilterInterface
    {
        if (!isset($this->map[$name])) {
            throw new \RuntimeException('No mapping defined for "' . $name . '"', 500);
        }

        if (is_string($this->map[$name])) {
            $name = $this->map[$name];

        } elseif (isset($this->map[$name]['name'])) {
            $name = $this->map[$name]['name'];

        } else {

            throw new \RuntimeException('No service name registered for "' . $name . '"', 500);
        }

        return $this->inputFilters->get($name);
    }

    private function getResponseStrategy($name) : callable
    {
        $strategy = $this->map[$name]['strategy'] ?? DefaultResponseStrategy::class;

        if (is_callable($strategy)) {
            return $strategy;
        }

        if (is_string($strategy)) {
            return new $strategy;
        }

        if (is_array($strategy) && !empty($strategy)) {
            $class = array_shift($strategy);
            $strategy = new $class(...$strategy);
            if ($strategy instanceOf ResponseStrategyInterface || is_callable($strategy)) {
                return $strategy;
            }
        }

        throw new \RuntimeException('Unsupported respone strategy for "' . $name . '"');
    }
}
