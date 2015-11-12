<?php

namespace Passwogen;

use Symfony\Component\Filesystem\Filesystem;

class Config
{
    public function __construct()
    {
        $configDir = self::configDir();
        $configFile = self::configFile();
        $defaultStorage = self::configDir() . '/secret.enc';

        $fs = new Filesystem();
        if (!$fs->exists($configDir)) {
            $fs->mkdir($configDir);
        }

        if (!$fs->exists($configFile)) {
            $fs->touch($configFile);
            $fs->dumpFile($configFile, <<<CONF
{
    "length": 16,
    "storage_path": "{$defaultStorage}"
}\n
CONF
            );
        }
    }

    /**
     * @return mixed
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
        return getenv("HOME") . '/.passwogen';
    }

    /**
     * @return string
     */
    protected function configFile()
    {
        return $this->configDir() . '/config.json';
    }
}
