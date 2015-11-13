<?php

use Passwogen\Command\Generate;
use Passwogen\Command\Show;
use Passwogen\Command\Update;
use Symfony\Component\Console\Application;

$console = new Application('Passwogen-CLI', '0.1');
$console->add(new Generate());
$console->add(new Update());
$console->add(new Show());

return $console;
