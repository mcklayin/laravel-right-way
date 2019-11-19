<?php

namespace Mcklayin\RightWay;

class CollectionMakeCommand extends AbstractGeneratorCommand
{
    protected $name = 'right-way:make:collection';

    protected $description = 'Create a new collection class';

    protected $path = 'Collections';

    protected $type = 'Collection';

    /**
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__.'/stubs/collection.stub';
    }
}
