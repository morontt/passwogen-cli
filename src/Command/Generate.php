<?php

namespace Passwogen\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Generate extends BaseCommand
{
    protected function configure(): void
    {
        $this
            ->addPasswordName()
            ->addViewOption()
            ->setName('generate')
            ->setDescription('Generate password')
            ->addOption('password', 'p', InputOption::VALUE_OPTIONAL, 'password')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $config = $this->getApplicationConfig();
        $storage = $this->getStorage($input, $output);

        $name = $input->getArgument('name');
        $passwordItem = null;

        try {
            $passwordItem = $storage->get($name);
        } catch (\Exception $e) {
            self::error($output, $e->getMessage());
        }

        if ($passwordItem === null) {
            $password = $input->getOption('password');
            if ($password === null) {
                $password = $this->generate($config['length']);
            }
            $storage->set($name, $password);

            $output->writeln('');
            $output->writeln(sprintf(
                'password: <comment>%s</comment>',
                $this->showPassword($password, $input->getOption('show-password'))
            ));
        } else {
            self::error($output, sprintf('key "%s" already exists', $name));
        }

        return 0;
    }
}
