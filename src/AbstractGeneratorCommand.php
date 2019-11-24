<?php

namespace Mcklayin\RightWay;

use Illuminate\Console\GeneratorCommand;

abstract class AbstractGeneratorCommand extends GeneratorCommand
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $path;

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->namespace === parent::rootNamespace() ? parent::rootNamespace() : $this->namespace;
    }
}
