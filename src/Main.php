<?php

namespace GA;

use GA\Service\Fitness;
use GA\Service\Reproduction;

final class Main
{
    private Reproduction $reproductionService;

    private Fitness $fitnessService;

    private ?Individual $bestIndividual = null;

    private int $generationCount = 0;

    private int $stagnantCount = 0;

    /** @var string[] */
    private array $demand;

    /** @var string[] */
    private array $supply;

    /** @var mixed[] */
    private array $config;

    /**
     * @param string[] $demand
     * @param string[] $supply
     * @param mixed[] $config
     */
    public function __construct(array $demand, array $supply, array $config)
    {
        $this->demand = $demand;
        $this->supply = $supply;
        $this->config = $config;
        $this->fitnessService = new Fitness(
            $this->demand,
            $this->supply
        );
        $this->reproductionService = new Reproduction(
            count($this->demand),
            $this->fitnessService,
            $this->config['chance-of-mutation'],
            $this->config['elitism']
        );
    }

    public function evolve(Population $population): Population
    {
        $this->generationCount++;
        $newPopulation = new Population($this->fitnessService);
        $parents = $population->getEliteParents();
        $offset = 0;

        if ($this->config['elitism']) {
            $newPopulation->addIndividual($parents[0]);
            $newPopulation->addIndividual($parents[1]);
            $offset = 2;
        }

        for ($i = $offset; $i < $this->config['pool-size']; $i++) {
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
        $population->fillPopulation(count($this->demand), count($this->supply), $this->config['pool-size']);

        return $population;
    }

    public function isMaxStagnantReached(): bool
    {
        return $this->stagnantCount >= $this->config['max-stagnant'];
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