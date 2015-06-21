<?php

namespace PronouncePHP\Build;

class Builder
{
    /**
     * Build display table
     *
     * @param Table $table, array $answer
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
}