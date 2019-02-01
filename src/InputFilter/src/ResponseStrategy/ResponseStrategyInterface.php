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
interface ResponseStrategyInterface
{
    public function __invoke(ServerRequestInterface $request, InputFilterInterface $filter) : ResponseInterface;
}
