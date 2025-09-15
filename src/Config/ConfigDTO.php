<?php

namespace Passwogen\Config;

class ConfigDTO
{
    public function __construct(public int $length, public string $storagePath)
    {
    }
}
