<?php

namespace Passwogen\Command;

use Passwogen\Config;
use Passwogen\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class BaseCommand extends Command
{
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    protected function askMasterPassword(InputInterface $input, OutputInterface $output)
    {
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

        return $helper->ask($input, $output, $question);
    }

    protected function getStorage(InputInterface $input, OutputInterface $output)
    {
        $configData = [];

        try {
            $config = new Config();
            $configData = $config->get();
        } catch(\Exception $e) {
            $this->error($output, $e->getMessage());
        }

        return new Storage($this->askMasterPassword($input, $output), $configData['storage_path']);
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

    /**
     * @param OutputInterface $output
     * @param $message
     */
    protected function error(OutputInterface $output, $message)
    {
        $error = '  ' . $message . '  ';
        $emptyLine = str_repeat(' ', strlen($error));

        $output->writeln('');
        $output->writeln("<error>{$emptyLine}<error>");
        $output->writeln("<error>{$error}<error>");
        $output->writeln("<error>{$emptyLine}<error>");

        exit(1);
    }
}
