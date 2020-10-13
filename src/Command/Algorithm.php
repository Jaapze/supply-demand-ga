<?php

namespace GA\Command;

use DateTime;
use GA\Main;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Algorithm extends Command
{
    protected string $commandName = 'ga:run-algorithm';

    protected string $description = 'run the algorithm';

    private Main $main;

    /** @var mixed[] */
    private array $config;

    /**
     * @param mixed[] $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->description);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->initialSetup();

        $time1 = microtime(true);
        $message = 'Generation: %d (Stagnant: %d) Fittest: %d/%d';
        $maxStagnantMessage = 'HALT! Exceeded %d stagnant generations';

        $population = $this->main->generateStartingPopulation();
        $currentFitness = $this->main->getMaxFitness();

        while (!$population->maxFitnessReached()) {
            $currentStagnant = $this->main->getStagnantCount();
            $population = $this->main->evolve($population);

            if ($this->main->getBestIndividual()->getFitness() < $currentFitness) {
                $currentFitness = $this->main->getBestIndividual()->getFitness();
                $output->writeln(
                    '<fg=green>' .
                    sprintf(
                        $message,
                        $this->main->getGenerationCount(),
                        $currentStagnant,
                        $this->main->getBestIndividual()->getFitness(),
                        $this->main->getMaxFitness()
                    )
                    . '</>'
                );
            }

            if ($this->main->isMaxStagnantReached()) {
                $output->writeln(
                    '<bg=red;options=bold>' .
                    sprintf($maxStagnantMessage, $this->config['max-stagnant'])
                    . '</>'
                );
                break;
            }
        }

        $time2 = microtime(true);
        $outputMessage = '[%s] Solution at generation: %d time: %ds %sGenes: %s %sScore: %d %s-----------------------------%s';
        $filledOutputMessage = sprintf(
            $outputMessage,
            (new DateTime())->format('Y-m-d H:i:s'),
            $this->main->getGenerationCount(),
            round($time2 - $time1, 2),
            PHP_EOL,
            implode(',', $this->main->getBestIndividual()->getGenes()),
            PHP_EOL,
            $this->main->getBestIndividual()->getFitness(),
            PHP_EOL,
            PHP_EOL
        );

        $output->writeln(PHP_EOL . $filledOutputMessage);
        $this->writeLog($filledOutputMessage);

        return Command::SUCCESS;
    }

    private function initialSetup(): void
    {
        $jsonData = json_decode(file_get_contents('./data/data.json'), true);
        $this->main = new Main($jsonData['demand'], $jsonData['supply'], $this->config);
    }

    private function writeLog(string $message): void
    {
        if (!is_dir('log/')) {
            mkdir('log');
        }
        file_put_contents('log/log_' . date("j.n.Y") . '.log', $message, FILE_APPEND);
    }
}