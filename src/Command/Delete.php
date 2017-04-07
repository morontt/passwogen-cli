<?php

namespace Passwogen\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Delete extends BaseCommand
{
    protected function configure()
    {
        $this
            ->addPasswordName()
            ->setName('delete')
            ->setDescription('Remove password')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $storage = $this->getStorage($input, $output);

        $name = $input->getArgument('name');
        $passwordItem = null;

        try {
            $passwordItem = $storage->get($name);
        } catch (\Exception $e) {
            $this->error($output, $e->getMessage());
        }

        if ($passwordItem !== null) {
            $storage->remove($name);

            $output->writeln('');
            $output->writeln(sprintf('remove: <comment>%s</comment>', $name));
        } else {
            $this->error($output, sprintf('key "%s" not found', $name));
        }
    }
}
