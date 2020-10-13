#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use GA\Command\CreateData;

$application = new Application();

$application->addCommands(
    [
        new CreateData()
    ]
);

$application->run();