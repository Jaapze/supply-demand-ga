<?php

use GA\Algorithm;

require_once('vendor/autoload.php');
require_once('src/Algorithm.php');

$fileContent = file_get_contents('./data/data.json');
$jsonData = json_decode($fileContent, true);
$algorithm = new Algorithm($jsonData['demand'], $jsonData['supply']);
$time1 = microtime(true);

$message = PHP_EOL . 'Generation: %d (Stagnant: %d) Fittest: %d/%d';
$maxStagnantMessage = PHP_EOL . 'HALT! Exceeded %d stagnant generations';
$population = $algorithm->generateStartingPopulation();
$currentFitness = $algorithm->getMaxFitness();
$currentStagnant = 0;

while (!$population->maxFitnessReached()) {
    $currentStagnant = $algorithm->getStagnantCount();
    $population = $algorithm->evolve($population);

    if ($algorithm->getBestIndividual()->getFitness() < $currentFitness) {
        $currentFitness = $algorithm->getBestIndividual()->getFitness();
        echo sprintf(
            $message,
            $algorithm->getGenerationCount(),
            $currentStagnant,
            $algorithm->getBestIndividual()->getFitness(),
            $algorithm->getMaxFitness()
        );
    }

    if ($algorithm->isMaxStagnantReached()) {
        echo sprintf($maxStagnantMessage, Algorithm::MAX_STAGNANT);
        break;
    }
}

$time2 = microtime(true);
$outputMessage = '[%s] Solution at generation: %d time: %ds %sGenes: %s %sScore: %d %s-----------------------------%s';
$filledOutputMessage = sprintf(
    $outputMessage,
    (new DateTime())->format('Y-m-d H:i:s'),
    $algorithm->getGenerationCount(),
    round($time2 - $time1, 2),
    PHP_EOL,
    implode(',', $algorithm->getBestIndividual()->getGenes()),
    PHP_EOL,
    $algorithm->getBestIndividual()->getFitness(),
    PHP_EOL,
    PHP_EOL
);

echo PHP_EOL . PHP_EOL . $filledOutputMessage;

if (!is_dir('log/')) {
    mkdir('log');
}
file_put_contents('log/log_' . date("j.n.Y") . '.log', $filledOutputMessage, FILE_APPEND);