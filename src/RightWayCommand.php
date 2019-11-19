<?php

namespace Mcklayin\RightWay;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;

class RightWayCommand extends Command
{
    protected $files;

    protected $composer;

    protected $appRootNamespace = 'App';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'right-way:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert fresh laravel installation to DDD format';

    /**
     * Create a new controller creator command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param \Illuminate\Support\Composer      $composer
     *
     * @return void
     */
    public function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct();

        $this->files = $files;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->createDomainLayer();
        $this->createApplicationLayer();
        $this->createServiceLayer();

        $this->composer->dumpAutoloads();

        $this->info('Done');
    }

    /**
     * Create Domain layer & first User domain.
     */
    private function createDomainLayer()
    {
        $domainLayerName = 'Domain';
        $domainNamespace = $this->appRootNamespace.'\\'.$domainLayerName;
        $this->call('right-way:make:domain', [
            'name'   => 'User',
            '--root' => $domainLayerName,
        ]);

        $this->prepareDefaultDomain(app_path($domainLayerName), $domainNamespace);

        $this->info('Domain layer created');
    }

    /**
     * Create Application layer.
     */
    private function createApplicationLayer()
    {
        $applicationLayerName = 'App';
        $this->makeDirectory(app_path($applicationLayerName));

        // Move Framework directories to new location
        $applicationLayerFolders = [
            'Console',
            'Http',
        ];

        foreach ($applicationLayerFolders as $folder) {
            $destPath = app_path($applicationLayerName.'/'.$folder);
            $this->moveDirectory(app_path($folder), $destPath);
        }

        $this->info('Application layer created');
    }

    /**
     * Create Service layer.
     */
    private function createServiceLayer()
    {
        $serviceLayerName = 'Service';
        $this->makeDirectory(app_path($serviceLayerName));

        $this->info('Service layer created');
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
     * @param $srcPath
     * @param $destPath
     *
     * @return void
     */
    protected function moveDirectory($srcPath, $destPath)
    {
        if ($this->files->isDirectory($srcPath)) {
            $this->files->moveDirectory($srcPath, $destPath);
        }
    }

    /**
     * @param $srcPath
     * @param $destPath
     *
     * @return void
     */
    protected function moveFile($srcPath, $destPath)
    {
        if (!$this->files->isDirectory($srcPath)) {
            $this->files->move($srcPath, $destPath);
        }
    }

    /**
     * @param $path
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function prepareDefaultDomain($path, $domainNamespace)
    {
        $this->prepareDefaultModels($path, $domainNamespace);
    }

    /**
     * @param string $path
     * @param string $domainNamespace
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function prepareDefaultModels($path, $domainNamespace)
    {
        $defaultsModels = [
            'User' => 'User/Models',
        ];

        foreach ($defaultsModels as $model => $modelPath) {
            $srcPath = app_path($model.'.php');

            if ($this->files->exists($srcPath)) {
                $destPath = $path.'/'.$modelPath.'/'.$model.'.php';
                $namespace = $domainNamespace.'\\'.$this->buildNamespace($modelPath);
                $this->buildClass(app_path($model.'.php'), $this->appRootNamespace, $namespace);
                $this->moveFile(app_path($model.'.php'), $destPath);
            }
        }
    }

    /**
     * @param $path
     * @param $fromNamespace
     * @param $toNamespace
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($path, $fromNamespace, $toNamespace)
    {
        $fileData = $this->files->get($path);
        $this->files->put($path, $this->replaceNamespace($fileData, $fromNamespace, $toNamespace));
    }

    /**
     * @param $data
     * @param $fromNamespace
     * @param $toNamespace
     *
     * @return mixed
     */
    protected function replaceNamespace(&$data, $fromNamespace, $toNamespace)
    {
        return str_replace($fromNamespace, $toNamespace, $data);
    }

    /**
     * @param $path
     *
     * @return mixed
     */
    protected function buildNamespace($path)
    {
        return str_replace('/', '\\', $path);
    }
}
