<?php

namespace Passwogen\Config;

use Symfony\Component\Filesystem\Filesystem;

class Config
{
    /**
     * @throws \JsonException
     */
    public function __construct()
    {
        $configDir = $this->configDir();
        $configFile = $this->configFile();
        $defaultStorage = json_encode($this->defaultStoragePath(), JSON_THROW_ON_ERROR);

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
     * @throws \JsonException
     * @throws \RuntimeException
     */
    public function get(): ConfigDTO
    {
        $data = json_decode(file_get_contents($this->configFile()), true, 512, JSON_THROW_ON_ERROR);
        if (!$data) {
            throw new \RuntimeException(sprintf('Invalid config file: %s', $this->configFile()));
        }

        return new ConfigDTO(
            $data['length'] ?? 16,
            $data['storage_path'] ?? $this->defaultStoragePath(),
        );
    }

    private function configDir(): string
    {
        $home = (DIRECTORY_SEPARATOR === '\\') ? getenv('USERPROFILE') : getenv('HOME');

        return $home . DIRECTORY_SEPARATOR . '.passwogen';
    }

    private function configFile(): string
    {
        return $this->configDir() . DIRECTORY_SEPARATOR . 'config.json';
    }

    private function defaultStoragePath(): string
    {
        return $this->configDir() . DIRECTORY_SEPARATOR . 'secret.enc';
    }
}
