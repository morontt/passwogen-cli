<?php

namespace Passwogen\Command;

use Passwogen\Config;
use Passwogen\Storage;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Update extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('update')
            ->setDescription('Update password')
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
        if ($storage->get($name) !== null) {
            $password = $this->generate($configData['length']);
            $storage->set($name, $password);

            $output->writeln('');
            $output->writeln(sprintf('password: <comment>%s</comment>', $password));
        } else {
            $this->error($output, sprintf('key "%s" not found', $name));
        }
    }
}
