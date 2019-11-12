<?php

namespace Mcklayin\RightWay;

use Symfony\Component\Console\Input\InputOption;

class ActionMakeCommand extends AbstractGeneratorCommand
{
    protected $name = 'right-way:make:action';

    protected $description = 'Create a new action class';

    protected $path = 'Actions';

    protected $type = 'Action';

    /**
     * @return string
     */
    protected function getStub(): string
    {
        return $this->option('sync')
            ? __DIR__.'/stubs/action.stub'
            : __DIR__.'/stubs/action-queued.stub';
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['sync', null, InputOption::VALUE_NONE, 'Indicates that action should be synchronous']
        ];
    }
}
