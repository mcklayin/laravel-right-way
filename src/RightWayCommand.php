<?php

namespace Mcklayin\RightWay;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;

class RightWayCommand extends AbstractCommand
{
    /**
     * @var Composer
     */
    protected $composer;

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
        parent::__construct($files);
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->createDomainLayer();
        $this->createApplicationLayer();
        $this->createInfrastructureLayer();

        $this->composer->dumpAutoloads();

        $this->info('Done');
    }

    /**
     * Create Domain layer & first User domain.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function createDomainLayer()
    {
        $domainLayerNamespace = config('rightway.domain_layer_namespace');
        $this->call('right-way:make:domain', [
            'name'   => 'User',
        ]);

        $this->prepareDefaultDomain($this->getPath($domainLayerNamespace), $domainLayerNamespace);

        $this->info('Domain layer created');
    }

    /**
     * Create Application layer.
     */
    private function createApplicationLayer()
    {
        $applicationLayerNamespace = config('rightway.application_layer_namespace');
        $this->makeDirectory($this->getPath($applicationLayerNamespace));

        // Move Framework directories to new location
        $applicationLayerFolders = [
            'Console',
            'Http',
        ];

        foreach ($applicationLayerFolders as $folder) {
            $destPath = app_path($applicationLayerNamespace.'/'.$folder);
            $this->moveDirectory(app_path($folder), $destPath);
        }

        $this->info('Application layer created');
    }

    /**
     * Create Service layer.
     */
    private function createInfrastructureLayer()
    {
        $infrastructureLayerNamespace = config('rightway.infrastructure_layer_namespace');
        $this->makeDirectory(app_path($infrastructureLayerNamespace));

        $this->info('Infrastructure layer created');
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
     * @param $domainNamespace
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

        $additionalChanges = [
            'User' => 'updateAuthProvidersUsersModel',
        ];

        foreach ($defaultsModels as $model => $modelPath) {
            $srcPath = app_path($model.'.php');

            if ($this->files->exists($srcPath)) {
                $destPath = $path.'/'.$modelPath.'/'.$model.'.php';
                $namespace = $domainNamespace.'\\'.$this->buildNamespace($modelPath);
                $this->buildClass(app_path($model.'.php'), $this->rootNamespace(), $namespace);
                $this->moveFile(app_path($model.'.php'), $destPath);

                // Apply additional changes
                if (isset($additionalChanges[$model]) && method_exists($this, $additionalChanges[$model])) {
                    $this->{$additionalChanges[$model]}($namespace);
                }
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

    /**
     * Update auth.providers.users.model in auth.php.
     *
     * @param $model
     */
    protected function updateAuthProvidersUsersModel($model)
    {
        $replace = 'App\User::class';
        $path = config_path('auth.php');
        $fileData = $this->files->get($path);
        $this->files->put($path, str_replace($replace, $model.'\User::class', $fileData));
    }

    /**
     * @param $namespace
     *
     * @return string
     */
    protected function getPath($namespace): string
    {
        $namespace = $this->replaceLaravelNamespace($namespace);

        return app_path($this->qualifyPath($namespace));
    }
}
