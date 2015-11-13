<?php

use Passwogen\Command\Generate;
use Passwogen\Command\Update;
use Symfony\Component\Console\Application;

$console = new Application('Passwogen-CLI', '0.1');
$console->add(new Generate());
$console->add(new Update());

return $console;
