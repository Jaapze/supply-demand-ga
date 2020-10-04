<?php

namespace GA;

final class Individual
{
    /** @var string[] */
    private array $genes;

    private int $fitness;

    public function generateRandomGenes(int $supplyCount, int $demandCount): void
    {
        $demandArray = range(0, $demandCount - 1);
        shuffle($demandArray);
        $this->genes = array_slice($demandArray, 0, $supplyCount);
    }

    /**
     * @return string[]
     */
    public function getGenes(): array
    {
        return $this->genes;
    }

    public function setGene(int $demandIndex, string $supplyIndex): void
    {
        $this->genes[$supplyIndex] = $demandIndex;
        $this->fitness = 0;
    }

    public function setFitness(int $fitness): void
    {
        $this->fitness = $fitness;
    }

    public function getFitness(): int
    {
        return $this->fitness;
    }
}