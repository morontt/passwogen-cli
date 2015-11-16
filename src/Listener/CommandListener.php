<?php

namespace Passwogen\Listener;

use Passwogen\Command\BaseCommand;
use Passwogen\Config;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

class CommandListener
{
    /**
     * @param ConsoleCommandEvent $event
     */
    public function beforeRun(ConsoleCommandEvent $event)
    {
        $command = $event->getCommand();

        try {
            $config = new Config();
            $configData = $config->get();
            $command->setConfig($configData);
        } catch(\Exception $e) {
            BaseCommand::error($event->getOutput(), $e->getMessage());
        }
    }
}
