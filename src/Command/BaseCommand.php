<?php

namespace Passwogen\Command;

use Passwogen\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class BaseCommand extends Command
{
    /**
     * @var array
     */
    protected $config = [];


    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param OutputInterface $output
     * @param $message
     */
    public static function error(OutputInterface $output, $message)
    {
        $error = '  ' . $message . '  ';
        $emptyLine = str_repeat(' ', strlen($error));

        $output->writeln('');
        $output->writeln("<error>{$emptyLine}<error>");
        $output->writeln("<error>{$error}<error>");
        $output->writeln("<error>{$emptyLine}<error>");

        exit(1);
    }

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

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return Storage
     */
    protected function getStorage(InputInterface $input, OutputInterface $output)
    {
        $config = $this->getConfig();
        return new Storage($this->askMasterPassword($input, $output), $config['storage_path']);
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
