<?php

declare(strict_types=1);

namespace LanguageGuesser\Handler;

use Psr\Container\ContainerInterface;

class GuessLanguageFactory
{
    public function __invoke(ContainerInterface $container) : GuessLanguage
    {
        return new GuessLanguage();
    }
}
