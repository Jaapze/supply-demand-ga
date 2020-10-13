<?php

namespace GA\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateData extends Command
{
    protected string $commandName = 'ga:create-data';

    protected string $description = 'create data for algorithm';

    protected string $numberOfSupplyArg = 'number-of-supply';

    protected string $numberOfDemandArg = 'number-of-demand';

    protected string $numberOfOptionsArg = 'number-of-options';

    protected function configure(): void
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->description)
            ->addArgument(
                $this->numberOfSupplyArg,
                InputArgument::REQUIRED
            )
            ->addArgument(
                $this->numberOfDemandArg,
                InputArgument::REQUIRED
            )
            ->addArgument(
                $this->numberOfOptionsArg,
                InputArgument::REQUIRED
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = [
            'supply' => [],
            'demand' => [],
        ];

        for ($i = 0; $i < (int)$input->getArgument($this->numberOfSupplyArg); $i++) {
            $data['supply'][] = $this->generateRandomData((int)$input->getArgument($this->numberOfOptionsArg));
        }

        for ($j = 0; $j < (int)$input->getArgument($this->numberOfDemandArg); $j++) {
            $data['demand'][] = $this->generateRandomData((int)$input->getArgument($this->numberOfOptionsArg));
        }

        $fp = fopen('data/data.json', 'w');
        fwrite($fp, json_encode($data));
        fclose($fp);

        return Command::SUCCESS;
    }

    private function generateRandomData(int $length): string
    {
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= rand(0, 1);
        }

        return $randomString;
    }
}