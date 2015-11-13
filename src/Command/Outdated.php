<?php

namespace Passwogen\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Outdated extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('outdated')
            ->setDescription('Show outdated passwords')
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
        $table->setHeaders(['Key', 'Last Update']);

        $rows = [];
        $items = [];

        try {
            $items = $storage->outdated();
        } catch(\Exception $e) {
            $this->error($output, $e->getMessage());
        }

        foreach ($items as $item) {
            $itemTime = \DateTime::createFromFormat('Y-m-d H:i:s', $item['time']);
            $rows[] = [
                $item['key'],
                sprintf('<comment>%s</comment>', $itemTime->format('d M Y')),
            ];
        }

        $table
            ->setRows($rows)
            ->render();
    }
}
