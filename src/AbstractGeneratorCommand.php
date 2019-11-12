<?php

namespace Mcklayin\RightWay;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

abstract class AbstractGeneratorCommand extends GeneratorCommand
{

    /**
     * @var string
     */
    protected $domainPath = 'Domain';

    protected $path = '';

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\\' . $this->domainPath;
    }

    private function afterLast($subject, $search)
    {
        return $search === '' ? $subject : array_reverse(explode($search, $subject))[0];
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        $name = ltrim($name, '\\/');

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        $name = str_replace('/', '\\', $name);


        $actionNamePart = $this->afterLast($name, '\\');
        $name = Str::replaceFirst($actionNamePart, $this->path . '\\' . $actionNamePart, $name);
        $path = $this->qualifyClass(
            $this->getDefaultNamespace(trim($rootNamespace, '\\')) . '\\' . $name
        );

        return $path;
    }
}