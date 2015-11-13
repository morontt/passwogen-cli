<?php

namespace Passwogen\Command;

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
        $storage = $this->getStorage($input, $output);

        $name = $input->getArgument('name');
        $passwordItem = null;

        try {
            $passwordItem = $storage->get($name);
        } catch(\Exception $e) {
            $this->error($output, $e->getMessage());
        }

        if ($passwordItem !== null) {
            $output->writeln('');
            $output->writeln(sprintf('password: <comment>%s</comment>', $passwordItem['password']));
        } else {
            $this->error($output, sprintf('key "%s" not found', $name));
        }
    }
}
