<?php

/**
 * Explode by comma.
 *
 * @param string $string
 *
 * @return array
 */
function explodeByComma($string)
{
    $string = strtoupper($string);

    return explode(',', $string);
}

/**
 * Find comments in CMU file.
 *
 * @param string $line
 *
 * @return bool
 */
function isComment($line)
{
    if (substr($line, 0, 3) === ';;;') {
        return true;
    }

    return false;
}

/**
 * Replace spaces in array strings with underscores.
 *
 * @param array $strings
 *
 * @return array
 */
function spacesToUnderscores($strings)
{
    $array = [];

    foreach ($strings as $string) {
        array_push($array, str_replace(' ', '_', $string));
    }

    return $array;
}

/**
 * Make an underscored word camel-case.
 *
 * @param string $string
 *
 * @return string
 */
function underscoreToCamelCase($string)
{
    $string = strtolower($string);

    $index = strpos($string, '_');

    $letter = strtoupper($string[$index + 1]);

    $string[$index + 1] = $letter;

    $string = str_replace('_', '', $string);

    return $string;
}

/**
 * Make a camel-cased word underscored.
 *
 * @param string $string
 *
 * @return string
 */
function camelCaseToUnderscore($string)
{
    preg_match_all('/[A-Z]/', $string, $matches, PREG_OFFSET_CAPTURE);

    if (empty($matches[0])) {
        return $string;
    }

    foreach ($matches[0] as $match) {
        $letter = $match[0];
        $index = $match[1];

        $string[$index] = strtolower($letter);

        $string = substr($string, 0, $index).'_'.substr($string, $index);
    }

    return $string;
}

/**
 * Get array keys and turn them into array of vaules.
 *
 * @param array $array
 *
 * @return array
 */
function getKeys($array)
{
    $first_item_key = array_keys($array)[0];

    $keys = array_keys($array[$first_item_key]);

    return $keys;
}
