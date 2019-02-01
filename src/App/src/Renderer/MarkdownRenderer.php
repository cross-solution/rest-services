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
use Zend\Expressive\Template\TemplatePath;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * ${CARET}
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo   write test
 */
class MarkdownRenderer implements TemplateRendererInterface
{
    const DEFAULT_NAMESPACE = '__default__';

    private $parser;
    private $paths;

    public function __construct(Markdown $parser)
    {
        $this->parser = $parser;
    }

    public function render(string $name, $params = []): string
    {
        if (false !== strpos($name, '::')) {
            list ($namespace, $name) = explode('::', $name, 2);
        } else {
            $namespace = self::DEFAULT_NAMESPACE;
        }

        if (!isset($this->paths[$namespace])) {
            throw new \RuntimeException('Could not find template. No path registered for namespace ' . $namespace);
        }

        if (!preg_match('~\.md$~', $name)) {
            $name .= '.md';
        }

        foreach ($this->paths[$namespace] as $path) {
            $file = $path . '/' . $name;

            if (file_exists($file) && is_readable($file)) {
                return $this->parser->transform(file_get_contents($file));
            }
        }

        throw new \RuntimeException('Could not find template. File ' . $file . ' does not exists.');
    }

    public function addPath(string $path, string $namespace = null): void
    {
        $namespace = $namespace ?? self::DEFAULT_NAMESPACE;

        $this->paths[$namespace][] = $path;
    }

    public function getPaths(): array
    {
        $result = [];
        foreach ($this->paths as $namespace => $paths) {
            if (self::DEFAULT_NAMESPACE == $namespace) { $namespace = null; }

            foreach ($paths as $path) {
                $result[] = new TemplatePath($path, $namespace);
            }
        }

        return $result;
    }

    public function addDefaultParam(string $templateName, string $param, $value): void
    {
        // Params not supported at the moment.
    }

}
