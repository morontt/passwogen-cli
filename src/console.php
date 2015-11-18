<?php

use Passwogen\Command\Find;
use Passwogen\Command\Generate;
use Passwogen\Command\Outdated;
use Passwogen\Command\Update;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;

$console = new Application('Passwogen-CLI', '0.2');
$console->add(new Generate());
$console->add(new Update());
$console->add(new Find());
$console->add(new Outdated());

$dispatcher = new EventDispatcher();
$dispatcher->addListener(ConsoleEvents::COMMAND, 'Passwogen\\Listener\\CommandListener::beforeRun');
$console->setDispatcher($dispatcher);

return $console;
