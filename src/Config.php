<?php

namespace Passwogen;

use Symfony\Component\Filesystem\Filesystem;

class Config
{
    public function __construct()
    {
        $configDir = $this->configDir();
        $configFile = $this->configFile();
        $defaultStorage = json_encode($this->configDir() . DIRECTORY_SEPARATOR . 'secret.enc');

        $fs = new Filesystem();
        if (!$fs->exists($configDir)) {
            $fs->mkdir($configDir);
        }

        if (!$fs->exists($configFile)) {
            $fs->touch($configFile);
            $fs->dumpFile($configFile, <<<CONF
{
    "length": 16,
    "storage_path": {$defaultStorage}
}\n
CONF
            );
        }
    }

    /**
     * @return mixed
     *
     * @throws \Exception
     */
    public function get()
    {
        $data = json_decode(file_get_contents($this->configFile()), true);
        if (!$data) {
            throw new \Exception(sprintf('Invalid config file: %s', $this->configFile()));
        }

        return $data;
    }

    /**
     * @return string
     */
    protected function configDir()
    {
        $home = (DIRECTORY_SEPARATOR === '\\') ? getenv('USERPROFILE') : getenv('HOME');

        return $home . DIRECTORY_SEPARATOR . '.passwogen';
    }

    /**
     * @return string
     */
    protected function configFile()
    {
        return $this->configDir() . DIRECTORY_SEPARATOR . 'config.json';
    }
}
