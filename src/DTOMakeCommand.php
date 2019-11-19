<?php

namespace Mcklayin\RightWay;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class DTOMakeCommand extends GeneratorCommand
{
    protected $name = 'right-way:make:dto';

    protected $description = 'Create a new data-transfer-object class';

    protected $path = 'DataTransferObjects';

    protected $type = 'Data Transfer Object';

    /**
     * @return string
     */
    protected function getStub(): string
    {
        return $this->option('request')
            ? __DIR__.'/stubs/dto-from-request.stub'
            : __DIR__.'/stubs/dto.stub';
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['request', null, InputOption::VALUE_NONE, 'Indicates that data transfer object should has from request method'],
        ];
    }
}
