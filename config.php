<?php

return [
    /*
    | Define database type
    | Options: mysql
    */
    'database' => 'mysql',

    /*
    | Enter connection detains for defined database type
    | 
    */
    'connections' => [
        "mysql" => [
            'driver' => 'mysql',
            'charset' => 'utf8',
            'host' => getenv('DB_HOST'),
            'database_name' => getenv('DB_DATABASE'),
            'table' => getenv('DE_TABLE'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD')
        ],

        "mysqlite" => [


        ]
    ],
];