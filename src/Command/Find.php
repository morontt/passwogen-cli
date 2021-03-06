<?php

namespace Passwogen\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Find extends BaseCommand
{
    protected function configure()
    {
        $this
            ->addPasswordName()
            ->addViewOption()
            ->setName('find')
            ->setDescription('Find passwords (by regexp)')
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
        $table = new Table($output);
        $table->setHeaders(array('Key', 'Password'));

        $rows = array();
        $items = array();

        try {
            $items = $storage->find($input->getArgument('name'));
        } catch (\Exception $e) {
            $this->error($output, $e->getMessage());
        }

        $showPassword = $input->getOption('show-password');

        foreach ($items as $item) {
            $rows[] = array(
                $item['key'],
                sprintf('<comment>%s</comment>', $this->showPassword($item['password'], $showPassword)),
            );
        }

        if (count($items) === 1) {
            $this->copyToClipboard($items[0]['password']);
        }

        $table
            ->setRows($rows)
            ->render();
    }
}
