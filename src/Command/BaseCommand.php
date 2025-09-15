<?php

namespace Passwogen\Command;

use Passwogen\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class BaseCommand extends Command
{
    /**
     * @var array
     */
    protected $config = array();

    /**
     * @return array
     */
    public function getApplicationConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setApplicationConfig(array $config): void
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
     * @return $this
     */
    protected function addPasswordName(): self
    {
        $this
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Name for password'
            )
        ;

        return $this;
    }

    /**
     * @return $this
     */
    protected function addViewOption()
    {
        $this->addOption('show-password', 's', InputOption::VALUE_NONE, 'Show password');

        return $this;
    }

    /**
     * @param string $password
     * @param bool $showPassword
     *
     * @return string
     */
    protected function showPassword($password, $showPassword)
    {
        if ($showPassword) {
            $result = $password;
        } else {
            $result = str_repeat('*', $this->config['length']);
        }

        return $result;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return mixed
     */
    protected function askMasterPassword(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $question = new Question('Enter your master password: ');
        $question->setValidator(function ($value) {
            if (trim($value) === '') {
                throw new \RuntimeException('The password can not be empty');
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
     *
     * @return Storage
     */
    protected function getStorage(InputInterface $input, OutputInterface $output)
    {
        $config = $this->getApplicationConfig();

        return new Storage($this->askMasterPassword($input, $output), $config['storage_path']);
    }

    /**
     * @param int $length
     *
     * @return string
     */
    protected function generate(int $length): string
    {
        do {
            $random = base64_encode(openssl_random_pseudo_bytes(256));
            $random = str_replace(array('+', '/', '0', 'O', 'I', 'l'), '', $random);

            $password = substr($random, 0, $length);
        } while (!$this->isStrong($password));

        $this->copyToClipboard($password);

        return $password;
    }

    /**
     * @param string $str
     *
     * @return bool
     */
    protected function isStrong(string $str): bool
    {
        return preg_match('/[a-z]/', $str) && preg_match('/\d/', $str) && preg_match('/[A-Z]/', $str);
    }

    /**
     * @param string $password
     */
    protected function copyToClipboard(string $password): void
    {
        if (PHP_OS === 'Linux' && shell_exec('which xclip')) {
            $p = popen('xclip -sel clip', 'w');
            fwrite($p, $password);
            pclose($p);
        }

        if (PHP_OS === 'Darwin' && shell_exec('which pbcopy')) {
            $p = popen('pbcopy', 'w');
            fwrite($p, $password);
            pclose($p);
        }

        if (DIRECTORY_SEPARATOR === '\\' && shell_exec('where clip 2>nul')) {
            $p = popen('clip', 'w');
            fwrite($p, $password);
            pclose($p);
        }
    }
}
