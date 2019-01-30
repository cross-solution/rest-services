<?php

declare(strict_types=1);

namespace LanguageGuesser\Handler;

use LanguageDetection\Language;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class GuessLanguage implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $params = $request->getParsedBody();

        $ld   = new Language($params['languages'] ?? ['de', 'en']);
        $result = $ld->detect($params['text'])->close();
        $lang = (array_keys($result))[0];

        return new JsonResponse([
            'language' => $lang,
            'result' => $result,
            'input' => $params,
        ]);
    }
}
