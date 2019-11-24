<?php

namespace Mcklayin\RightWay;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

abstract class AbstractDomainGeneratorCommand extends AbstractGeneratorCommand
{
    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->namespace = config('rightway.domain_layer_namespace') . '\\';
    }
}
