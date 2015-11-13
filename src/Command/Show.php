<?php

namespace Passwogen\Command;

use Passwogen\Config;
use Passwogen\Storage;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Show extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('show')
            ->setDescription('Show password')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Name for password'
            )
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new Config();
        $configData = $config->get();

        $storage = new Storage($this->askMasterPassword($input, $output), $configData['storage_path']);

        $name = $input->getArgument('name');
        $passwordItem = $storage->get($name);
        if ($passwordItem !== null) {
            $output->writeln('');
            $output->writeln(sprintf('password: <comment>%s</comment>', $passwordItem['password']));
        } else {
            $this->error($output, sprintf('key "%s" not found', $name));
        }
    }
}
