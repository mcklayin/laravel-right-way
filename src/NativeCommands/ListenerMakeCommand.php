<?php

namespace Mcklayin\RightWay\NativeCommands;

use Illuminate\Support\Str;
use Mcklayin\RightWay\AbstractDomainGeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class ListenerMakeCommand extends AbstractDomainGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:listener';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new event listener class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Listener';

    /**
     * @var string
     */
    protected $path = 'Listeners';

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $event = $this->option('event');

        if (!Str::startsWith($event, [
            $this->laravel->getNamespace(),
            'Illuminate',
            '\\',
        ])) {
            $event = $this->rootNamespace().$this->getLayerFrom($event).'\Events\\'.$this->getBaseName($event);
        }

        $stub = str_replace(
            'DummyEvent', class_basename($event), parent::buildClass($name)
        );

        return str_replace(
            'DummyFullEvent', trim($event, '\\'), $stub
        );
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('queued')) {
            return $this->option('event')
                        ? __DIR__.'/stubs/listener-queued.stub'
                        : __DIR__.'/stubs/listener-queued-duck.stub';
        }

        return $this->option('event')
                    ? __DIR__.'/stubs/listener.stub'
                    : __DIR__.'/stubs/listener-duck.stub';
    }

    /**
     * Determine if the class already exists.
     *
     * @param string $rawName
     *
     * @return bool
     */
    protected function alreadyExists($rawName)
    {
        return class_exists($rawName);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['event', 'e', InputOption::VALUE_OPTIONAL, 'The event class being listened for'],

            ['queued', null, InputOption::VALUE_NONE, 'Indicates the event listener should be queued'],
        ];
    }
}
