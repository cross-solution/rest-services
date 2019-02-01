<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Plates\PlatesRenderer;
use Zend\Expressive\Router;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Expressive\Twig\TwigRenderer;
use Zend\Expressive\ZendView\ZendViewRenderer;

class HomePageHandler implements RequestHandlerInterface
{

    /** @var null|TemplateRendererInterface */
    private $template;

    private $homepages;

    public function __construct(
        ?TemplateRendererInterface $template = null,
        array $homepages = null
    ) {
        $this->template      = $template;
        $this->homepages     = $homepages;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {

        $content = $this->template->render('app::home');

        foreach ($this->homepages as $view) {
            $content .= $this->template->render($view);
        }

        $content = '<!DOCTYPE html><html><head><style type="text/css">body { font-size: 14px; }</style></head><body>' .$content . '</body></html>';

        return new HtmlResponse($content);
    }
}
