<?php
/**
 * CrossServices
 *
 * @filesource
 * @copyright 2019 Cross Solution <http://cross-solution.de>
 */

/** */

namespace App\Renderer;

use Michelf\Markdown;
use Psr\Container\ContainerInterface;

/**
 * ${CARET}
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo   write test
 */
class MarkdownRendererFactory
{
    public function __invoke(ContainerInterface $container) : MarkdownRenderer
    {
        $config = $container->has('config') ? $container->get('config') : null;
        $config = $config['templates'] ?? [];

        $parser = new Markdown(); // @todo Create own Factory and make it configurable
        $renderer = new MarkdownRenderer($parser);

        foreach ($config['paths'] as $namespace => $paths) {
            if (is_numeric($namespace)) {
                $namespace = null;
            }

            foreach ($paths as $path) {
                $renderer->addPath($path, $namespace);
            }
        }

        return $renderer;

    }

}
