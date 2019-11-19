<?php

namespace Mcklayin\RightWay;

class QueryBuilderMakeCommand extends AbstractGeneratorCommand
{
    protected $name = 'right-way:make:query-builder';

    protected $description = 'Create a new query builder class';

    protected $path = 'QueryBuilders';

    protected $type = 'Query Builder';

    /**
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__.'/stubs/query-builder.stub';
    }
}
