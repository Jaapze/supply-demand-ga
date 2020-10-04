<?php

use GA\Main;

require_once('vendor/autoload.php');
require_once('src/Main.php');

$fileContent = file_get_contents('./data/data.json');
$jsonData = json_decode($fileContent, true);
$maxScore = count($jsonData['supply']) * strlen($jsonData['supply'][0]);
$mainProgram = new Main($jsonData['demand'], $jsonData['supply']);

$maxStagnant = 600;
$time1 = microtime(true);
$generationCount = 0;
$generationStagnant = 0;

$population = $mainProgram->generateStartingPopulation();
$lastFitScore = $population->getFittest()->getFitness();

while ($population->getFittest()->getFitness() > 0) {
    $generationCount++;
    $population = $mainProgram->evolve($population);
    $currentFitness = $population->getFittest()->getFitness();

    if ($currentFitness < $lastFitScore) {
        echo "\n Generation: " . $generationCount . " (Stagnant:" . $generationStagnant . ") Fittest: " . $currentFitness . "/" . $maxScore;
        $generationStagnant = 0;

        $lastFitScore = $currentFitness;
    } else {
        $generationStagnant++;
    }

    if ($generationStagnant > $maxStagnant) {
        echo "\n HALT! Exceeded " . $maxStagnant . " stagnant generations";
        break;
    }
}

$time2 = microtime(true);
$extraInfo = false;

echo "\n";
echo "\nSolution at generation: " . $generationCount . " time: " . round($time2 - $time1, 2) . "s";
echo "\n---------------------------------------------------------\n";
echo "\nGenes   : " . implode(',', $population->getFittest()->getGenes());
echo "\nScore   : " . $population->getFittest()->getFitness();
echo "\n---------------------------------------------------------\n";

if ($extraInfo) {
    foreach ($population->getFittest()->getGenes() as $key => $gene) {
        $supplyItem = str_split($jsonData['supply'][$key]);
        $demandItem = str_split($jsonData['demand'][$gene]);

        $fitness = 0;
        foreach ($demandItem as $charKey => $optionChar) {
            if ($supplyItem[$charKey] !== $optionChar) {
                $fitness++;
            }
        }

        echo "\nSupply       : " . $jsonData['supply'][$key];
        echo "\nDemand       : " . $jsonData['demand'][$gene];
        echo "\nDifference   : " . $fitness;
        echo "\n----";
    }
    echo "\n---------------------------------------------------------\n";
}