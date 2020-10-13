#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use GA\Command\Algorithm;
use Symfony\Component\Console\Application;
use GA\Command\CreateData;

$application = new Application();
$config = include('config/config.php');

$application->addCommands(
    [
        new CreateData(),
        new Algorithm($config)
    ]
);

$application->run();