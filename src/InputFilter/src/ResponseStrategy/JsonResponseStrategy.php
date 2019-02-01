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
use Zend\Diactoros\Response\JsonResponse;
use Zend\InputFilter\InputFilterInterface;

/**
 * ${CARET}
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo   write test
 */
class JsonResponseStrategy implements ResponseStrategyInterface
{
    public function __invoke(ServerRequestInterface $request, InputFilterInterface $filter) : ResponseInterface
    {
        $messages = [];
        foreach ($filter->getMessages() as $name => $errors) {
            $messages[$name] = array_values($errors);
        }

        return new JsonResponse(
            [
                'error' => 'invalid input data',
                'reason' => 'At least one of the provided input data failed to validate.',
                'details' => $messages,
            ],
            400
        );
    }

}
