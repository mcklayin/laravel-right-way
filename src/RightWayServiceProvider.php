<?php

namespace Mcklayin\RightWay;

use Illuminate\Support\ServiceProvider;
use Mcklayin\RightWay\NativeCommands\ChannelMakeCommand;
use Mcklayin\RightWay\NativeCommands\ConsoleMakeCommand;
use Mcklayin\RightWay\NativeCommands\ControllerMakeCommand;
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

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/rightway.php' => config_path('rightway.php'),
        ], 'config');
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
        $this->app->extend('command.channel.make', function () {
            return new ChannelMakeCommand(app('files'));
        });

        $this->app->extend('command.console.make', function () {
            return new ConsoleMakeCommand(app('files'));
        });

        $this->app->extend('command.controller.make', function () {
            return new ControllerMakeCommand(app('files'));
        });

        $this->app->extend('command.model.make', function () {
            return new ModelMakeCommand(app('files'));
        });
    }
}
