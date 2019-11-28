<?php

namespace Mcklayin\RightWay\NativeCommands;

use Illuminate\Support\Str;
use Mcklayin\RightWay\AbstractApplicationGeneratorCommand;

class MiddlewareMakeCommand extends AbstractApplicationGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:middleware';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new middleware class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Middleware';

    /**
     * @var string
     */
    protected $path = 'Middleware';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/middleware.stub';
    }

    /**
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        if (!Str::contains($this->getNameInput(), '/')) {
            return $rootNamespace.'\Http';
        }

        return $rootNamespace;
    }
}
