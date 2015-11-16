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
        if (!function_exists('gzencode')) {
            $event->getOutput()->writeln('');
            $event->getOutput()->writeln('Install and enable the <comment>Zlib</comment> extension.');
            exit(1);
        }

        if (!function_exists('openssl_random_pseudo_bytes')) {
            $event->getOutput()->writeln('');
            $event->getOutput()->writeln('Install and enable the <comment>OpenSSL</comment> extension.');
            exit(1);
        }

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
