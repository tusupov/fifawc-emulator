<?php

namespace FifaWC;

use PDO,
    Exception;

/**
 * Работа с БД
 * @package FifaWC
 */
class DB {

    private $pdo;

    /**
     * DB constructor.
     * @param $config
     */
    public function __construct($config) {

        //Настройки БД по умолчанию
        $config = array_merge(
            array(
                "host"     => "127.0.0.1",
                "db"       => "test",
                "username" => "root",
                "password" => "",
                "charset"  => "utf8",
                "port"     => "3306",
            ),
            $config
        );

        $this->prefix = $config["prefix"];

        $dsn = "mysql:host={$config["host"]};dbname={$config["db"]};charset={$config["charset"]}";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {

            //Подключаемся к БД
            $this->pdo = new PDO($dsn, $config["username"], $config["password"], $opt);

        } catch (Exception $e) {

            echo $e->getMessage();
            die();

        }

    }

    /**
     * Запрос
     * @param $queryStr
     * @return \PDOStatement
     * @throws Exception
     */
    public function query($queryStr) {

        try {

            $result = $this->pdo->query($queryStr);
            return $result;

        } catch (Exception $e) {

            throw new Exception($e->getMessage(), $e->getCode());

        }

    }

    /**
     * Запрос на добавление
     * @param $queryStr
     * @return PDO
     * @throws Exception
     */
    public function insert($queryStr) {

        try {

            $this->pdo->query($queryStr);
            return $this->pdo;

        } catch (Exception $e) {

            throw new Exception($e->getMessage(), $e->getCode());

        }

    }

}