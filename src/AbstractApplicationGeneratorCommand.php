<?php

namespace Mcklayin\RightWay;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

abstract class AbstractApplicationGeneratorCommand extends AbstractGeneratorCommand
{
    /**
     * Create a new controller creator command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->namespace = config('rightway.application_layer_namespace').'\\';
    }

    /**
     * Returns the portion of string specified by the start and length parameters.
     *
     * @param string   $string
     * @param int      $start
     * @param int|null $length
     *
     * @return string
     */
    public function substr($string, $start, $length = null): string
    {
        return mb_substr($string, $start, $length, 'UTF-8');
    }

    /**
     * Get the portion of a string before the last occurrence of a given value.
     *
     * @param string $subject
     * @param string $search
     *
     * @return string
     */
    public function beforeLast($subject, $search): string
    {
        if ($search === '') {
            return $subject;
        }

        $pos = mb_strrpos($subject, $search);

        if ($pos === false) {
            return $subject;
        }

        return $this->substr($subject, 0, $pos);
    }

    /**
     * @return string
     */
    protected function getApplicationName(): string
    {
        return explode('/', $this->getNameInput())[0];
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function qualifyName($name): string
    {
        return str_replace('/', '\\', $name);
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getPath($name)
    {
        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param string $name
     *
     * @return string
     */
    protected function qualifyClass($name): string
    {
        $name = ltrim($name, '\\/');

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        $name = $this->qualifyName($name);

        $name = Str::replaceFirst($this->getBaseName(), $this->path.'\\'.$this->getBaseName(), $name);
        $path = $this->qualifyClass(
            $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name
        );

        return $path;
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param string $stub
     * @param string $name
     *
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $stub = str_replace(
            $this->getReplacePlaceholders(),
            $this->getReplacers($name),
            $stub
        );

        return $this;
    }

    /**
     * @return array
     */
    protected function getReplacePlaceholders(): array
    {
        return ['DummyNamespace', 'DummyRootNamespace', 'NamespacedDummyUserModel'];
    }

    /**
     * @param $name
     *
     * @return array
     */
    protected function getReplacers($name): array
    {
        return [$this->getNamespace($name), $this->rootNamespace(), $this->userProviderModel()];
    }

    /**
     * @return string
     */
    protected function getBaseName(): string
    {
        return class_basename($this->getNameInput());
    }
}
