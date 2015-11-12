<?php

namespace Passwogen\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Generate extends Command
{
    protected function configure()
    {
        $this
            ->setName('generate')
            ->setDescription('Generate password')
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
        $name = $input->getArgument('name');

        $password = $this->generate();

        $output->writeln(sprintf('password: <comment>%s</comment>', $password));
    }

    /**
     * @return string
     */
    protected function generate()
    {
        do {
            $random = base64_encode(openssl_random_pseudo_bytes(256));
            $random = str_replace(['+', '/', '0', 'O', 'I', 'l'], '', $random);

            $password = substr($random, 0, 16);
        } while (!$this->isStrong($password));

        return $password;
    }

    /**
     * @param $str
     * @return bool
     */
    protected function isStrong($str)
    {
        return preg_match('/[a-z]/', $str) && preg_match('/[0-9]/', $str) && preg_match('/[A-Z]/', $str);
    }
}
