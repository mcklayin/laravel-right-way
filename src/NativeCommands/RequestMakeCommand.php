<?php

namespace Mcklayin\RightWay\NativeCommands;

use Illuminate\Support\Str;
use Mcklayin\RightWay\AbstractApplicationGeneratorCommand;

class RequestMakeCommand extends AbstractApplicationGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form request class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Request';

    /**
     * @var string
     */
    protected $path = 'Requests';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/request.stub';
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
