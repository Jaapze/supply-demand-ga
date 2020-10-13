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
You can find a configuration file in the config folder with the following configurations:

title | description
----- | -----------
chance-of-mutation | chance of mutation per gene
pool-size | Size of population per generation
elitism | Transfer the parents to the next generation
max-stagnant | Max amount of stagnant

## Commands
Run the following command for creating test data:
```
$ php app ga:create-data [number-of-supply] [number-of-demand] [number-of-options]
```

This is for actually running the algorithm:
```
$ php app ga:run-algorithm
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

[2020-10-13 19:43:09] Solution at generation: 1560 time: 62s
Genes: 473,348,228,144,462,312,165,234,323,84,125,264,215,351,390,461,29,373,435,424,395,384,160
Score: 30
-----------------------------
```
**Generation:** Current generation.<br/>
**Stagnant:** The number of generations went by without a better individual.<br/>
**Fittest:** The score of the fittest individual (0 is most fit).

## Known issues
- The algorithm does not work when you have less demand than supply