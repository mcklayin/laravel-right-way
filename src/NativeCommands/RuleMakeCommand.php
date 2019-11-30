<?php

namespace Mcklayin\RightWay\NativeCommands;

use Mcklayin\RightWay\AbstractDomainGeneratorCommand;

class RuleMakeCommand extends AbstractDomainGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:rule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new validation rule';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Rule';

    /**
     * @var string
     */
    protected $path = 'Rules';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/rule.stub';
    }
}
