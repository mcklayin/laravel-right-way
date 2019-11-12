<?php

namespace Mcklayin\RightWay;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class DomainMakeCommand extends Command
{
    protected $files;


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'right-way:make:domain 
                            {name : Domain name}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make domain using DDD';

    private $domainPath = 'Domain';

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');

        $this->createStructure($name);
    }

    /**
     * @param $name
     */
    protected function createStructure($name)
    {
        $path = $this->getPath($name);
        $this->makeDirectory($path);
    }

    /**
     * @param $name
     * @return string
     */
    protected function getPath($name)
    {
        return $this->laravel['path'] . '/' . $this->domainPath . '/' . $name;
    }

    /**
     * @param $path
     * @return mixed
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }
}
