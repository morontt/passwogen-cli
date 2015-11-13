<?php

namespace Passwogen\Command;

use Passwogen\Config;
use Passwogen\Storage;
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
        $config = new Config();
        $configData = $config->get();

        $storage = new Storage($this->askMasterPassword($input, $output), $configData['storage_path']);

        $table = new Table($output);
        $table->setHeaders(['Key', 'Password']);

        $rows = [];
        $items = $storage->find($input->getArgument('name'));
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
