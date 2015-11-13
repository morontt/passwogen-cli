<?php

namespace Passwogen\Command;

use Passwogen\Config;
use Passwogen\Storage;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
            ->addOption('password', 'p', InputOption::VALUE_OPTIONAL, 'password')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configData = [];

        try {
            $config = new Config();
            $configData = $config->get();
        } catch(\Exception $e) {
            $this->error($output, $e->getMessage());
        }

        $storage = new Storage($this->askMasterPassword($input, $output), $configData['storage_path']);

        $name = $input->getArgument('name');
        $passwordItem = null;

        try {
            $passwordItem = $storage->get($name);
        } catch(\Exception $e) {
            $this->error($output, $e->getMessage());
        }

        if ($passwordItem !== null) {
            $password = $input->getOption('password');
            if ($password === null) {
                $password = $this->generate($configData['length']);
            }

            $storage->set($name, $password);

            $output->writeln('');
            $output->writeln(sprintf('password: <comment>%s</comment>', $password));
        } else {
            $this->error($output, sprintf('key "%s" not found', $name));
        }
    }
}
