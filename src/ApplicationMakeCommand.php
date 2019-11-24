<?php

namespace Mcklayin\RightWay;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ApplicationMakeCommand extends Command
{
    protected $files;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'right-way:make:app 
                            {name : Application name}
                            {--root : Applicaiton path}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Application using DDD';

    private $rootPath = 'App';

    /**
     * Create a new controller creator command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     *
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

        $root = $this->option('root');

        if ($root) {
            $this->rootPath = $root;
        }

        $this->createStructure($name);

        $this->info("Application {$name} created");
    }

    /**
     * @param $name
     */
    protected function createStructure($name)
    {
        $path = $this->getPath($name);
        $this->makeDirectory($path);

        $structure = [
            'Controllers',
            'Middleware',
            'Requests',
        ];

        foreach ($structure as $folder) {
            $this->makeDirectory($path.'/'.$folder);
        }
    }

    /**
     * @param $name
     *
     * @return string
     */
    protected function getPath($name)
    {
        return $this->laravel['path'].'/'.$this->rootPath.'/'.$name;
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
}
