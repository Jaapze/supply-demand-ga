<?php

namespace GA;

use GA\Service\Fitness;

final class Main
{
    /** @var float */
    private const CHANCE_OF_MUTATION = 0.07;

    /** @var int */
    private const POOL_SIZE = 350;

    /** @var int */
    private const ELITISM = false;

    /** @var string[] */
    private array $demand;

    /** @var string[] */
    private array $supply;

    private Fitness $fitnessService;

    private Algorithm $algorithm;

    /**
     * @param string[] $demand
     * @param string[] $supply
     */
    public function __construct(array $demand, array $supply)
    {
        $this->demand = $demand;
        $this->supply = $supply;
        $this->fitnessService = new Fitness($demand, $supply);
        $this->algorithm = new Algorithm(
            count($this->demand),
            $this->fitnessService,
            self::CHANCE_OF_MUTATION,
            self::POOL_SIZE,
            self::ELITISM
        );
    }

    public function generateStartingPopulation(): Population
    {
        $population = new Population($this->fitnessService);
        $population->fillPopulation(count($this->demand), count($this->supply), self::POOL_SIZE);

        return $population;
    }

    public function evolve(Population $population): Population
    {
        return $this->algorithm->evolve($population);
    }
}