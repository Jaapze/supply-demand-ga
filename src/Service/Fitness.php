<?php

namespace GA\Service;

use GA\Individual;

final class Fitness
{
    /** @var string[] */
    private array $demand;

    /** @var string[] */
    private array $supply;

    /**
     * @param string[] $demand
     * @param string[] $supply
     */
    public function __construct(array $demand, array $supply)
    {
        $this->demand = $demand;
        $this->supply = $supply;
    }

    public function calculate(Individual $individual): int
    {
        $fitness = 0;

        foreach ($individual->getGenes() as $key => $gene) {
            $demandItem = str_split($this->demand[$gene]);
            $supplyItem = str_split($this->supply[$key]);

            foreach ($demandItem as $charKey => $optionChar) {
                if ($supplyItem[$charKey] !== $optionChar) {
                    $fitness++;
                }
            }
        }

        return $fitness;
    }
}