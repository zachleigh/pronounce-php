<?php

namespace PronouncePHP\Hyphenate;

class Hyphenator
{
    private $patterns;

    private $exceptions;

    private $tree;

    /**
     * Construct
     *
     * This class is an adaptation of Ned Batchelder's python interpretation of Frank Liang's hyphenation algorithm
     * http://nedbatchelder.com/code/modules/hyphenate.py
     *
     * @param array $patterns, array $patterns
     * @return void
    */
    public function __construct()
    {
        $this->patterns = $this->loadPatterns();

        $this->exceptions = [];

        $this->loadExceptions();

        $this->tree = $this->buildTree();
    }

    /**
     * Load patterns from patterns.php
     *
     * @return void
    */
    private function loadPatterns()
    {
        $liang_patterns = getLiangPatterns();

        $kuiken_patterns = getKuikenPatterns();

        return array_merge($liang_patterns, $kuiken_patterns);
    }

    /**
     * Load exceptions from patterns.php
     *
     * @return void
    */
    private function loadExceptions()
    {
        $exceptions = getExceptions();

        foreach ($exceptions as $exception)
        {
            $points = $this->getExceptionPoints($exception);

            $exception = str_replace('-', '', $exception);

            $this->exceptions[$exception] = $points;

        }
    }

    /**
     * Build tree of patterns
     *
     * @return void
    */
    private function buildTree()
    {
        $tree = array();

        foreach($this->patterns as $pattern) 
        {
            $characters = preg_replace('/[0-9]+/', '', $pattern);

            $characters = array_reverse(str_split($characters));

            $points = $this->getPoints($pattern);

            $temp = array();

            foreach($characters as $index => $character) 
            {
                if($index == 0) 
                {
                    $temp[] = $points;
                }

                $temp = array(
                    $character => $temp
                );
            }

            $tree = array_merge_recursive($tree, $temp);
        }

        return $tree;
    }

    /**
     * Get points array for string
     *
     * @param string $pattern
     * @return array
    */
    private function getPoints($pattern)
    {
        $points = [];

        foreach (preg_split("/[.a-z]/", $pattern) as $i)
        {
            if (empty($i))
            {
                array_push($points, 0);

                continue;
            }

            array_push($points, $i);
        }

        return $points;
    }

    /**
     * Get points array for exceptions
     *
     * @param string $pattern
     * @return array
    */
    private function getExceptionPoints($exception)
    {
        $points = [0];

        foreach (preg_split("/[.a-z]/", $exception) as $i)
        {
            if (empty($i))
            {
                array_push($points, 0);

                continue;
            }

            array_push($points, 1);
        }

        return $points;
    }

    /**
     * Break words into pieces, broken at hyphenation points
     *
     * @param string $word
     * @return string
    */
    public function hyphenateWord($word)
    {
        if (strlen($word) <= 4)
        {
            return strtolower($word);
        }

        if (array_key_exists(strtolower($word), $this->exceptions))
        {
            $points = $this->exceptions[strtolower($word)];
        }
        else
        {
            $string = '.' . strtolower($word) . '.';

            $points = $this->getPoints($string);

            for ($word_index = 0; $word_index < strlen($string); $word_index++)
            {
                $tree = $this->tree;

                $substring = substr($string, $word_index);

                for ($substring_index = 0; $substring_index < strlen($substring); $substring_index++)
                {
                    $character = $substring[$substring_index];

                    if (array_key_exists($character, $tree))
                    {
                        $tree = $tree[$character];

                        if (array_key_exists(0, $tree))
                        {
                            $point_array = $tree[0];

                            for ($point_index = 0; $point_index < count($point_array); $point_index++)
                            {
                                $points[$word_index + $point_index] = max($points[$word_index + $point_index], $point_array[$point_index]);
                            }
                        }
                    }
                    else
                    {
                        break;
                    }
                }
            }

            end($points);
            $last = key($points);

            prev($points);
            $second_last = key($points);

            reset($points);

            $points[1] = $points[2] = $points[$last] = $points[$second_last] = 0;
        }

        $pieces = '';

        $zipped = $this->zip(strtolower($word), array_slice($points, 2));

        foreach ($zipped as $tuple)
        {
            $letter = $tuple[0];
            $points = $tuple[1];

            $pieces .= $letter;

            if ($points % 2)
            {
                $pieces .= ' ';
            }
        }

        return $pieces;
    }

    /**
     * Approximation of python zip function
     *
     * @param string/array $one, array $two
     * @return array
    */
    private function zip($one, array $two)
    {
        if (!is_array($one))
        {
            $one = str_split($one);
        }

        $index = count($one);

        $zipped = [];

        for ($i = 0; $i < $index; $i++)
        {
            $tuple = [$one[$i], $two[$i]];

            array_push($zipped, $tuple);
        }

        return $zipped;
    }
}