<?php

namespace Nitixx\Controllers;


class DatabaseManager
{
    /**
     * @var \PDO Current connection
     */
    private static $connection;

    /**
     * @inheritDoc
     */
    private function __construct() {}

    /**
     * @inheritDoc
     */
    private function __clone() { }

    /**
     * Return the current connection or create one if it does not exist
     * @return \PDO
     */
    public static  function getConnection(){
        if(static::$connection == null){
            $config =  include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config'. DIRECTORY_SEPARATOR . 'database.php';
            static::$connection = new \PDO($config['dsn'], $config['user'], $config['password']);
            static::$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        return static::$connection;
    }
}