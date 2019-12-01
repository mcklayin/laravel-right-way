<?php

namespace Mcklayin\RightWay;

use Illuminate\Filesystem\Filesystem;

class DomainMakeCommand extends AbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'right-way:make:domain 
                            {name : Domain name}
                            {--root : Domain path}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make domain using DDD';

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
        $this->namespace = config('rightway.domain_layer_namespace');
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

        $this->info("Domain {$name} created");
    }

    /**
     * @param $name
     */
    protected function createStructure($name)
    {
        $path = $this->getPath($name);
        $this->makeDirectory($path);

        $structure = [
            'Actions',
            'Broadcasting',
            'Collections',
            'DataTransferObjects',
            'Events',
            'Exceptions',
            'Jobs',
            'Listeners',
            'Mail',
            'Models',
            'Notifications',
            'Observers',
            'Operations',
            'Policies',
            'QueryBuilders',
            'Rules',
        ];

        foreach ($structure as $folder) {
            $this->makeDirectory($path.'/'.$folder);
        }
    }
}
