<?php
namespace Template;

use Symfony\Component\Templating\PhpEngine;

class PhpEngineExtended extends PhpEngine implements EngineInterfaceExtended
{
    public function __construct($parser, $loader) {
        parent::__construct($parser, $loader);
    }
}

