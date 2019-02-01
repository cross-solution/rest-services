<?php
/**
 * CrossServices
 *
 * @filesource
 * @copyright 2019 Cross Solution <http://cross-solution.de>
 */

/** */

namespace InputFilter\ResponseStrategy;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * ${CARET}
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo   write test
 */
class DefaultResponseStrategy implements ResponseStrategyInterface
{
    public function __invoke(ServerRequestInterface $request, InputFilterInterface $filter) : ResponseInterface
    {
        var_dump(__METHOD__);
        $contentType = $request->getHeader('Content-Type')[0] ?: 'unknown';

        if (false !== strpos($contentType, 'json')) {
            return (new JsonResponseStrategy)($request, $filter);
        }

        // Let the default error handling mechanism handle this.
        throw new \RuntimeException('Invalid input data', 400);
    }
}
