<?php

namespace Passwogen\Command;

use Passwogen\Config;
use Passwogen\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

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
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new Config();
        $configData = $config->get();

        $helper = $this->getHelper('question');

        $question = new Question('Enter your master password: ');
        $question->setValidator(function ($value) {
            if (trim($value) == '') {
                throw new \Exception('The password can not be empty');
            }
            return $value;
        });
        $question->setHidden(true);
        $question->setMaxAttempts(3);

        $storage = new Storage($helper->ask($input, $output, $question), $configData['storage_path']);

        $name = $input->getArgument('name');
        if ($storage->get($name) === null) {
            $password = $this->generate($configData['length']);
            $storage->set($name, $password);
        } else {
            throw new \Exception(sprintf('key "%s" already exists', $name));
        }

        $output->writeln('');
        $output->writeln(sprintf('password: <comment>%s</comment>', $password));
    }

    /**
     * @param int $length
     * @return string
     */
    protected function generate($length)
    {
        do {
            $random = base64_encode(openssl_random_pseudo_bytes(256));
            $random = str_replace(['+', '/', '0', 'O', 'I', 'l'], '', $random);

            $password = substr($random, 0, $length);
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
