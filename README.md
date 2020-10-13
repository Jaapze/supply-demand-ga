# Genetic Algorithm experiment

The idea of this project is to test what I can do with
a Genetic Algorithm. To keep it general I used 'supply'
and 'demand' as values, and both values have 'options'.

## Example:
**Supply:** An object wanted by demand, this object has options.<br/>
**Demand:** A person seeking for an object with certain options.

We can now use this algorithm to 
distribute people (demand) among objects (supply) and
match them by chosen options.

## Configuration
title | description
----- | -----------
CHANCE_OF_MUTATION | chance of mutation per gene
POOL_SIZE | Size of population per generation
ELITISM | Transfer the parents to the next generation
MAX_STAGNANT | Max amount of stagnant

## Commands
Run the following command for creating test data:
```
$ php app ga:create-data [number-of-supply] [number-of-demand] [number-of-options]
```

This is for actually running the command:
```
$ php index.php
```

## Output example
```
...
Generation: 813 (Stagnant:7) Fittest: 37/500
Generation: 857 (Stagnant:43) Fittest: 36/500
Generation: 871 (Stagnant:13) Fittest: 35/500
Generation: 896 (Stagnant:24) Fittest: 34/500
Generation: 918 (Stagnant:21) Fittest: 33/500
HALT! Exceeded 600 stagnant generations

Solution at generation: 1519 time: 61.25s
---------------------------------------------------------

Genes   : 473,265,373,444,462,312,496,234,86,103,230,360,6,271,139,323,170,430,435,424,395,384,160,168,182,451,32,478,15,398,294,289,216,179,273,401,178,441,146,378,226,379,64,252,69,431,177,148,120,51
Score   : 33
---------------------------------------------------------
```
**Generation:** Current generation.<br/>
**Stagnant:** The number of generations went by without a better individual.<br/>
**Fittest:** The score of the fittest individual (0 is most fit).

## Known issues
- The algorithm does not work when you have less demand than supply