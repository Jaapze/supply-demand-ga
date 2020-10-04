<?php

namespace GA\Service;

use GA\Individual;

final class Reproduction
{
    private Fitness $fitnessService;

    private float $chanceOfMutation;

    private bool $elitism;

    public function __construct(Fitness $fitnessService, float $changeOfMutation, bool $elitism)
    {
        $this->fitnessService = $fitnessService;
        $this->chanceOfMutation = $changeOfMutation;
        $this->elitism = $elitism;
    }


    public function crossover(Individual $individual, Individual $individual2): Individual
    {
        $maxCrossover = (int) round(count($individual->getGenes()) / 2);
        $childIndividual = new Individual();
        $crossedOver = 0;

        foreach ($individual->getGenes() as $supplyKey => $demandKey) {
            if (
                !in_array($demandKey, $individual2->getGenes())
                && $crossedOver < $maxCrossover
            ) {
                $childIndividual->setGene($demandKey, $supplyKey);
                $crossedOver++;
            }
            $childIndividual->setGene($demandKey, $supplyKey);
        }

        $childIndividual->setFitness($this->fitnessService->calculate($childIndividual));

        return $childIndividual;
    }

    public function mutate(Individual $individual, int $demandCount): Individual
    {
        $demandSet = range(0, $demandCount - 1);
        $randomDemandSet = array_diff($demandSet, $individual->getGenes());

        if (empty($randomDemandSet)) {
            return $individual;
        }

        foreach ($individual->getGenes() as $key => $gene) {
            if (mt_rand(0, 1000) / 1000 <= $this->chanceOfMutation) {
                $newGene = array_rand($randomDemandSet, 1);
                $individual->setGene($randomDemandSet[$newGene], $key);
                unset($randomDemandSet[$newGene]);
            }
        }

        $individual->setFitness($this->fitnessService->calculate($individual));

        return $individual;
    }
}