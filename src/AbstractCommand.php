<?php

namespace Mcklayin\RightWay;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

abstract class AbstractCommand extends Command
{
    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @var string
     */
    protected $namespace;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
        parent::__construct();
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace(): string
    {
        return $this->laravel->getNamespace();
    }

    /**
     * @return mixed
     */
    abstract public function handle();

    /**
     * @param $path
     *
     * @return string
     */
    protected function qualifyPath($path): string
    {
        return str_replace('\\', '/', $path);
    }

    /**
     * @param $path
     *
     * @return mixed
     */
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    /**
     * @param $namespace
     *
     * @return string
     */
    protected function replaceLaravelNamespace($namespace): string
    {
        return Str::replaceFirst($this->rootNamespace(), '', $namespace);
    }

    /**
     * @param $name
     *
     * @return string
     */
    protected function getPath($name): string
    {
        $namespace = $this->replaceLaravelNamespace($this->namespace);

        return $this->qualifyPath($this->laravel['path'].'/'.$namespace.'/'.$name);
    }
}
