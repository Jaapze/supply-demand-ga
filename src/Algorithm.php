<?php

namespace GA;

use GA\Service\Fitness;
use GA\Service\Reproduction;

final class Algorithm
{
    private Reproduction $reproductionService;

    private int $demandCount;

    private Fitness $fitnessService;

    private float $chanceOfMutation;

    private int $poolSize;

    private bool $elitism;

    public function __construct(
        int $demandCount,
        Fitness $fitnessService,
        $chanceOfMutation = 0.2,
        $poolSize = 200,
        $elitism = true
    ) {
        $this->demandCount = $demandCount;
        $this->fitnessService = $fitnessService;
        $this->chanceOfMutation = $chanceOfMutation;
        $this->poolSize = $poolSize;
        $this->elitism = $elitism;
        $this->reproductionService = new Reproduction(
            $this->fitnessService,
            $this->chanceOfMutation,
            $this->elitism
        );
    }

    public function evolve(Population $population): Population
    {
        $newPopulation = new Population($this->fitnessService);
        $population->sortIndividuals();
        $offset = 0;
        $mommy = $population->getIndividuals()[0];
        $daddy = $population->getIndividuals()[1];

        if ($this->elitism) {
            $newPopulation->addIndividual($mommy);
            $newPopulation->addIndividual($daddy);
            $offset = 2;
        }

        for ($i = $offset; $i < $this->poolSize; $i++) {
            $child = $this->reproductionService->crossover($mommy, $daddy);
            $child = $this->reproductionService->mutate($child, $this->demandCount);
            $newPopulation->addIndividual($child);
        }

        return $newPopulation;
    }
}