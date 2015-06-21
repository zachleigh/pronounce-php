<?php

namespace PronouncePHP\Build;

class Builder
{
    /**
     * Build display table
     *
     * @param Table $table, array $answers
     * @return Table
    */
    public function buildTable($table, array $answers)
    {
        $headers = [];

        $rows = [];

        foreach ($answers as $answer)
        {
            $row = [];

            foreach ($answer as $header => $row_item) 
            {                
                if (!in_array($header, $headers))
                {
                    array_push($headers, $header);
                }

                array_push($row, $row_item);
            }

            array_push($rows, $row);
        }

        $table->setHeaders($headers)
              ->setRows($rows);
        
        return $table;
    }

    /**
     * Build display string
     *
     * @param array $answers
     * @return string
    */
    public function buildString(array $answers)
    {
        $string = '';

        foreach ($answers as $answer)
        {
            foreach ($answer as $key => $value) 
            {                
                $string .= '<info>' . $key . ': </info>' . $value . ' ';
            }

            $string .= "\n";
        }

        return $string;
    }

    /**
     * Build file ouput line
     *
     * @param array $answer
     * @return string
    */
    public function buildFileOutput(array $answer)
    {
        $string = '';

        foreach ($answer as $key => $value) 
        {                
            $string .= $value . ' / ';
        }

        return $string;
    }
}