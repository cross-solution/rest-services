<?php

declare(strict_types=1);

namespace InputFilter\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router\RouteResult;
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
        /* @var \Zend\InputFilter\InputFilterInterface $filter */

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

        return new JsonResponse(
            [
                'status' => 'failure',
                'reason' => 'invalid input',
                'details' => $filter->getMessages()
            ],
            400
        );
    }

    private function extractData(ServerRequestInterface $request)
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

    private function loadInputFilter($name)
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
}
