<?php

if ($argc !== 4) {
    die('This command needs 3 parameters (numberOfHouses, numberOfPersons, numberOfSelection)');
}

$data = [
    'supply' => [],
    'demand' => [],
];

function generateOptionData(int $length)
{
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= rand(0, 1);
    }
    return $randomString;
}

for ($i = 0; $i < (int)$argv[1]; $i++) {
    $data['supply'][] = generateOptionData((int)$argv[3]);
}

for ($j = 0; $j < (int)$argv[2]; $j++) {
    $data['demand'][] = generateOptionData((int)$argv[3]);
}

$fp = fopen('data/data.json', 'w');
fwrite($fp, json_encode($data));
fclose($fp);