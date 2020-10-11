<?php

namespace GA;

use GA\Service\Fitness;
use GA\Service\Reproduction;

final class Algorithm
{
    /** @var float */
    private const CHANCE_OF_MUTATION = 0.01;

    /** @var int */
    private const POOL_SIZE = 350;

    /** @var int */
    private const ELITISM = true;

    /** @var int */
    public const MAX_STAGNANT = 600;

    private Reproduction $reproductionService;

    /** @var string[] */
    private array $demand;

    /** @var string[] */
    private array $supply;

    private ?Individual $bestIndividual = null;

    private int $generationCount = 0;

    private int $stagnantCount = 0;

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
        $this->generationCount++;
        $newPopulation = new Population($this->fitnessService);
        $parents = $population->getEliteParents();
        $offset = 0;

        if (self::ELITISM) {
            $newPopulation->addIndividual($parents[0]);
            $newPopulation->addIndividual($parents[1]);
            $offset = 2;
        }

        for ($i = $offset; $i < self::POOL_SIZE; $i++) {
            $newIndividual = $this->reproductionService->crossover($parents[0], $parents[1]);
            $newIndividual = $this->reproductionService->mutate($newIndividual);
            $newPopulation->addIndividual($newIndividual);
        }

        $this->setBestIndividual($newPopulation);

        return $newPopulation;
    }

    public function generateStartingPopulation(): Population
    {
        $population = new Population($this->fitnessService);
        $population->fillPopulation(count($this->demand), count($this->supply), self::POOL_SIZE);

        return $population;
    }

    public function isMaxStagnantReached(): bool
    {
        return $this->stagnantCount >= self::MAX_STAGNANT;
    }

    public function getMaxFitness(): int
    {
        return count($this->supply) * strlen($this->supply[0]);
    }

    public function getBestIndividual(): ?Individual
    {
        return $this->bestIndividual;
    }

    public function getGenerationCount(): int
    {
        return $this->generationCount;
    }

    public function getStagnantCount(): int
    {
        return $this->stagnantCount;
    }

    private function hasBetterIndividual(Population $population): bool
    {
        return $this->bestIndividual === null
            || $population->getBestIndividual()->getFitness() < $this->bestIndividual->getFitness();
    }

    private function setBestIndividual(Population $population): void
    {
        if ($this->hasBetterIndividual($population)) {
            $this->stagnantCount = 0;
            $this->bestIndividual = $population->getBestIndividual();
        }

        $this->stagnantCount++;
    }
}