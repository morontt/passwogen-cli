<?php

namespace Passwogen\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Outdated extends BaseCommand
{
    protected function configure(): void
    {
        $this
            ->setName('outdated')
            ->setDescription('Show outdated passwords')
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
        $table = new Table($output);
        $table->setHeaders(array('Key', 'Last Update'));

        $rows = array();
        $items = array();

        try {
            $items = $storage->outdated();
        } catch (\Exception $e) {
            self::error($output, $e->getMessage());
        }

        foreach ($items as $item) {
            $itemTime = \DateTime::createFromFormat('Y-m-d H:i:s', $item['time']);
            $rows[] = array(
                $item['key'],
                sprintf('<comment>%s</comment>', $itemTime->format('d M Y')),
            );
        }

        $table
            ->setRows($rows)
            ->render();

        return 0;
    }
}
