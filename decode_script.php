<?php

$filename = 'assets/users_table.csv';
$results = parseCSV($filename);

foreach ($results as $name => $score) {
    echo "$name: $score\n";
}

/**
 * Decodes a score using the given digits and encoded value.
 *
 * @param string $digits The string of digits used for decoding.
 * @param string $encoded The encoded value to be decoded.
 * @return int The decoded score.
 */
function decodeScore($digits, $encoded) {
    $base = strlen($digits);
    $score = 0;

    $encoded = strrev($encoded);
    for ($i = 0; $i < strlen($encoded); $i++) {
       $char = $encoded[$i];
       $index = strpos($digits, $char);
       $score += $index * pow($base, $i);
    }

    return $score;
}

/**
 * Parse a CSV file and return an associative array of results.
 *
 * @param string $filename The path to the CSV file.
 * @return array An associative array where the keys are the names and the values are the scores.
 */
function parseCSV($filename) {
    $results = [];

    if (($handle = fopen($filename, "r")) !== false) {
        while (($data = fgetcsv($handle)) !== false) {
            list($name, $digits, $encoded) = $data;
            $score = decodeScore($digits, $encoded);
            $results[$name] = $score;
        }
        fclose($handle);
    }

    return $results;
}

?>