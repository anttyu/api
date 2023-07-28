<?php

namespace System\DB;
include_once ('config.php');

/**
 * Class Database
 * @package System\DB
 */
class Database
{
    // укажите свои учетные данные базы данных
    /**
     * @var
     */
    public $conn;

    // получаем соединение с БД

    /**
     * @return \PDO|null
     */
    public function getConnection()
    {
        $this->conn = null;
        try
        {
            $this->conn = new \PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
            $this->conn->exec("set names utf8");
        }
        catch (\PDOException $exception)
        {
            echo "Ошибка подключения: " . $exception->getMessage();
        }

        return $this->conn;
    }
}