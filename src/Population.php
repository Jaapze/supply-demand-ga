<?php

namespace GA;

use GA\Service\Fitness;

final class Population
{
    /** @var Individual[] */
    private array $individuals = [];

    private Fitness $fitnessService;

    public function __construct(Fitness $fitnessService)
    {
        $this->fitnessService = $fitnessService;
    }

    public function fillPopulation(int $demandCount, int $supplyCount, int $poolSize): void
    {
        for ($i = 0; $i < $poolSize; $i++) {
            $individual = new Individual();
            $individual->generateRandomGenes($demandCount, $supplyCount);
            $individual->setFitness($this->fitnessService->calculate($individual));
            $this->individuals[] = $individual;
        }
    }

    public function getFittest(): Individual
    {
        $fittest = reset($this->individuals);
        foreach ($this->individuals as $individual) {
            if ($fittest->getFitness() >= $individual->getFitness()) {
                $fittest = $individual;
            }
        }

        return $fittest;
    }

    /**
     * @return Individual[]
     */
    public function getEliteParents(): array
    {
        $sortedIndividuals = $this->individuals;
        usort(
            $sortedIndividuals,
            function (Individual $a, Individual $b) {
                if ($a->getFitness() === $b->getFitness()) {
                    return 0;
                }
                return ($a->getFitness() < $b->getFitness()) ? -1 : 1;
            }
        );

        return array_slice($sortedIndividuals, 0, 2);
    }

    public function addIndividual(Individual $individual): void
    {
        $this->individuals[] = $individual;
    }
}