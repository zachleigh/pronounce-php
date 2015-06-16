<?php

/**
 * Build the word pronunciation string
 *
 * @param string $line
 * @return bool
*/
function isComment($line)
{
    if (substr($line, 0, 3) === ';;;')
    {
        return true;
    }

    return false;
}

/**
 * Convert array to string
 *
 * @param array $array
 * @return string
*/
function arrayToString($array) {
    $string = '';

    foreach ($array as $key => $value) {
        $string .= $value;
    }

    return $string;
}

/**
 * Check if key is expected key
 *
 * @param array $array, string $expected_key
 * @return bool
*/
function checkKey($array, $expected_key) {
    return key($array) === $expected_key;
}

/**
 * Flatten multi-dimensional array
 *
 * @param array $array
 * @return array
*/
function flatten($array) {
    $flat_array = [];

    foreach ($array as $sub_array) {
        foreach ($sub_array as $key => $value) {
            array_push($flat_array, $value);
        }
    }

    return $flat_array;
}
 
/**
 * Remove bracketed items
 *
 * @return string
*/
function removeBrackets($string) 
{
    $has_brackets = strpos($string, '{');

    if ($has_brackets) {
        return preg_replace("/\{[^)]+\}/", "", $string);
    }
}