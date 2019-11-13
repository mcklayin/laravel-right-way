<?php

namespace Mcklayin\RightWay;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use Mcklayin\RightWay\NativeCommands\ModelMakeCommand;

class RightWayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DomainMakeCommand::class,
                DTOMakeCommand::class,
                QueryBuilderMakeCommand::class,
                CollectionMakeCommand::class,
                ActionMakeCommand::class,
                RightWayCommand::class,
            ]);

            $this->overrideInternalCommands();
        }
    }

    public function provides(): array
    {
        return [
            DomainMakeCommand::class,
            DTOMakeCommand::class,
            QueryBuilderMakeCommand::class,
            CollectionMakeCommand::class,
            ActionMakeCommand::class,
            RightWayCommand::class,
        ];
    }

    private function overrideInternalCommands()
    {
        $this->app->extend('command.model.make', function () {
            return new ModelMakeCommand(app('files'));
        });
    }
}
