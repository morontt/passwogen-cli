#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

set_time_limit(0);

$console = require __DIR__ . '/../src/console.php';
$console->run();
