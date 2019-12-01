<?php

namespace Mcklayin\RightWay;

use Illuminate\Filesystem\Filesystem;

class InfrastructureMakeCommand extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'right-way:make:infrastructure 
                            {name : Service name}
                            {--root : Service path}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make service using DDD';

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
        $this->namespace = config('rightway.infrastructure_layer_namespace');
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

        $this->makeDirectory($this->getPath($name));

        $this->info("Infrastructure Service {$name} created");
    }
}
