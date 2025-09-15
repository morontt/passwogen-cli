<?php

namespace Passwogen\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Update extends BaseCommand
{
    protected function configure(): void
    {
        $this
            ->addPasswordName()
            ->addViewOption()
            ->addLengthOption()
            ->setName('update')
            ->setDescription('Update password')
            ->addOption('password', 'p', InputOption::VALUE_OPTIONAL, 'Password')
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
        $storage = $this->getStorage($input, $output);

        $name = $input->getArgument('name');
        $passwordItem = null;

        try {
            $passwordItem = $storage->get($name);
        } catch (\Exception $e) {
            self::error($output, $e->getMessage());
        }

        if ($passwordItem !== null) {
            $password = $input->getOption('password');
            if ($password === null) {
                $password = $this->generate($this->passwordLength($input, $output));
            }

            $storage->set($name, $password);

            $output->writeln('');
            $output->writeln(sprintf(
                'password: <comment>%s</comment>',
                $this->showPassword($password, $input->getOption('show-password'))
            ));
        } else {
            self::error($output, sprintf('key "%s" not found', $name));
        }

        return 0;
    }
}
