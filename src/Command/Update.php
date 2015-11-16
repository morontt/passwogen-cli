<?php

namespace Passwogen\Command;

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
        $config = $this->getConfig();
        $storage = $this->getStorage($input, $output);

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
                $password = $this->generate($config['length']);
            }

            $storage->set($name, $password);

            $output->writeln('');
            $output->writeln(sprintf('password: <comment>%s</comment>', $password));
        } else {
            $this->error($output, sprintf('key "%s" not found', $name));
        }
    }
}
