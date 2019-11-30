<?php

namespace Mcklayin\RightWay;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

abstract class AbstractGeneratorCommand extends GeneratorCommand
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * Folder path relative to namespace
     *
     * @var string
     */
    protected $path;

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace(): string
    {
        return $this->isLaravelNamespace() ? parent::rootNamespace() : $this->namespace;
    }

    /**
     * @return bool
     */
    private function isLaravelNamespace(): bool
    {
        return $this->namespace === parent::rootNamespace();
    }

    /**
     * @param string $input
     *
     * @return string
     */
    protected function getLayerFrom($input): string
    {
        return explode('\\', $this->qualifyName($input))[0];
    }

    /**
     * @return string
     */
    protected function getLayer(): string
    {
        $input = $this->gatherInput();

        return $this->getLayerFrom($input);
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
        if (!$this->isLaravelNamespace()) {
            $name = Str::replaceFirst(parent::rootNamespace(), '', $name);
        }

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * @param string|null $input
     *
     * @return string
     */
    protected function getBaseName($input = null): string
    {
        $input = $this->gatherInput($input);

        return class_basename($input);
    }

    /**
     * @param string|null $input
     *
     * @return string
     */
    private function gatherInput($input = null): string
    {
        return $input ?? $this->getNameInput();
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

        if (Str::contains($name, '\\')) {
            $name = Str::replaceFirst($this->getLayer(), $this->getLayer().'\\'.$this->path, $name);
        } else {
            $name = $this->path.'\\'.$name;
        }

        $path = $this->qualifyClass(
            $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name
        );

        return $path;
    }
}
