<?php

namespace GA;

use GA\Service\Fitness;
use GA\Service\Reproduction;

final class Algorithm
{
    /** @var float */
    private const CHANCE_OF_MUTATION = 0.07;

    /** @var int */
    private const POOL_SIZE = 350;

    /** @var int */
    private const ELITISM = false;

    private Reproduction $reproductionService;

    /** @var string[] */
    private array $demand;

    /** @var string[] */
    private array $supply;

    private Fitness $fitnessService;

    /**
     * @param string[] $demand
     * @param string[] $supply
     */
    public function __construct(array $demand, array $supply)
    {
        $this->demand = $demand;
        $this->supply = $supply;
        $this->fitnessService = new Fitness(
            $this->demand,
            $this->supply
        );
        $this->reproductionService = new Reproduction(
            count($this->demand),
            $this->fitnessService,
            self::CHANCE_OF_MUTATION,
            self::ELITISM
        );
    }

    public function evolve(Population $population): Population
    {
        $newPopulation = new Population($this->fitnessService);
        $population->sortIndividuals();
        $offset = 0;
        $mommy = $population->getIndividuals()[0];
        $daddy = $population->getIndividuals()[1];

        if (self::ELITISM) {
            $newPopulation->addIndividual($mommy);
            $newPopulation->addIndividual($daddy);
            $offset = 2;
        }

        for ($i = $offset; $i < self::POOL_SIZE; $i++) {
            $child = $this->reproductionService->crossover($mommy, $daddy);
            $child = $this->reproductionService->mutate($child);
            $newPopulation->addIndividual($child);
        }

        return $newPopulation;
    }

    public function generateStartingPopulation(): Population
    {
        $population = new Population($this->fitnessService);
        $population->fillPopulation(count($this->demand), count($this->supply), self::POOL_SIZE);

        return $population;
    }
}