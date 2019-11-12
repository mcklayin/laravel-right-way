<?php

namespace Mcklayin\RightWay;

use Illuminate\Support\ServiceProvider;

class RightWayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DomainMakeCommand::class,
                DTOMakeCommand::class,
                QueryBuilderMakeCommand::class,
                ActionMakeCommand::class,
            ]);
        }
    }

    public function provides(): array
    {
        return [
            DomainMakeCommand::class,
            DTOMakeCommand::class,
            QueryBuilderMakeCommand::class,
            ActionMakeCommand::class,
        ];
    }
}
