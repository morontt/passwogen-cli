<?php

namespace Passwogen\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Find extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('find')
            ->setDescription('Find password')
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
        $table = new Table($output);
        $table->setHeaders(['Key', 'Password']);

        $rows = [];
        $items = [];

        try {
            $items = $storage->find($input->getArgument('name'));
        } catch(\Exception $e) {
            $this->error($output, $e->getMessage());
        }

        foreach ($items as $item) {
            $rows[] = [
                $item['key'],
                sprintf('<comment>%s</comment>', $item['password']),
            ];
        }

        $table
            ->setRows($rows)
            ->render();
    }
}
