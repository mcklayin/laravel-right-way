<?php

namespace Mcklayin\RightWay;

use Illuminate\Filesystem\Filesystem;

class ApplicationMakeCommand extends AbstractCommand
{
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
        $this->namespace = config('rightway.application_layer_namespace');
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
            $this->namespace = $root;
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
            'Resources',
        ];

        foreach ($structure as $folder) {
            $this->makeDirectory($path.'/'.$folder);
        }
    }
}
