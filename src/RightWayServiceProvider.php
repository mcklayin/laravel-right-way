<?php

namespace Mcklayin\RightWay;

use Illuminate\Support\ServiceProvider;
use Mcklayin\RightWay\NativeCommands\ChannelMakeCommand;
use Mcklayin\RightWay\NativeCommands\ConsoleMakeCommand;
use Mcklayin\RightWay\NativeCommands\ControllerMakeCommand;
use Mcklayin\RightWay\NativeCommands\EventMakeCommand;
use Mcklayin\RightWay\NativeCommands\ExceptionMakeCommand;
use Mcklayin\RightWay\NativeCommands\FactoryMakeCommand;
use Mcklayin\RightWay\NativeCommands\JobMakeCommand;
use Mcklayin\RightWay\NativeCommands\ListenerMakeCommand;
use Mcklayin\RightWay\NativeCommands\MailMakeCommand;
use Mcklayin\RightWay\NativeCommands\MiddlewareMakeCommand;
use Mcklayin\RightWay\NativeCommands\ModelMakeCommand;
use Mcklayin\RightWay\NativeCommands\NotificationMakeCommand;
use Mcklayin\RightWay\NativeCommands\ObserverMakeCommand;
use Mcklayin\RightWay\NativeCommands\PolicyMakeCommand;
use Mcklayin\RightWay\NativeCommands\RequestMakeCommand;
use Mcklayin\RightWay\NativeCommands\ResourceMakeCommand;
use Mcklayin\RightWay\NativeCommands\RuleMakeCommand;

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

        $this->app->extend('command.event.make', function () {
            return new EventMakeCommand(app('files'));
        });

        $this->app->extend('command.exception.make', function () {
            return new ExceptionMakeCommand(app('files'));
        });

        $this->app->extend('command.factory.make', function () {
            return new FactoryMakeCommand(app('files'));
        });

        $this->app->extend('command.job.make', function () {
            return new JobMakeCommand(app('files'));
        });

        $this->app->extend('command.listener.make', function () {
            return new ListenerMakeCommand(app('files'));
        });

        $this->app->extend('command.mail.make', function () {
            return new MailMakeCommand(app('files'));
        });

        $this->app->extend('command.middleware.make', function () {
            return new MiddlewareMakeCommand(app('files'));
        });

        $this->app->extend('command.model.make', function () {
            return new ModelMakeCommand(app('files'));
        });

        $this->app->extend('command.notification.make', function () {
            return new NotificationMakeCommand(app('files'));
        });

        $this->app->extend('command.observer.make', function () {
            return new ObserverMakeCommand(app('files'));
        });

        $this->app->extend('command.policy.make', function () {
            return new PolicyMakeCommand(app('files'));
        });

        $this->app->extend('command.request.make', function () {
            return new RequestMakeCommand(app('files'));
        });

        $this->app->extend('command.resource.make', function () {
            return new ResourceMakeCommand(app('files'));
        });

        $this->app->extend('command.rule.make', function () {
            return new RuleMakeCommand(app('files'));
        });
    }
}
