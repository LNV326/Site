<?php
namespace Template;

use Smarty;
use InvalidArgumentException;
use RuntimeException;

class SmartyEngine extends Smarty implements EngineInterfaceExtended
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * {@inheritdoc}
     * @see \Symfony\Component\Templating\EngineInterface::exists()
     */
    public function exists($name)
    {
        return $this->templateExists($name);
    }

    /**
     *
     * {@inheritdoc}
     * @see \Symfony\Component\Templating\EngineInterface::supports()
     */
    public function supports($name)
    {
        if (false !== $pos = strrpos($name, '.'))
            $engine = substr($name, $pos + 1);

        return 'tpl' === $engine;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Symfony\Component\Templating\EngineInterface::render()
     */
    public function render($name, array $parameters = [])
    {
        // Check template file exists
        if (! $this->templateExists($name))
            throw new InvalidArgumentException(sprintf('The template "%s" does not exist.', $name));

        // Assign params for template
        if (! empty($parameters))
            $this->assign($parameters);

        // Render
        try {
            $content = $this->fetch($name);
        } catch (\Throwable $e) {
            throw new RuntimeException(sprintf('The template "%s" cannot be rendered.', $name), null, $e);
        }

        // Unsen all assigned params
        if (! empty($parameters))
            $this->clearAssign(array_keys($parameters));
        return $content;
    }
}

