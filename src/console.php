<?php

use Passwogen\Command\Generate;
use Symfony\Component\Console\Application;

$console = new Application('Passwogen-CLI', '0.1');
$console->add(new Generate());

return $console;
