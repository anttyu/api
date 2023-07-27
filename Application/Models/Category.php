<?php

namespace Application\Models;

class Category
{
    // соединение с БД и таблицей "categories"
    private $conn;
    private $table_name = "categories";

    // свойства объекта
    public int $id;
    public string $name;
    public string $description;
    public string $created;
    public string $modified;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = "SELECT
                id, name, description
            FROM
                " . $this->table_name . "
            ORDER BY
                name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function read_one($id)
    {
        $query = "SELECT id, name, description FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $id);

        $stmt->execute();

        return $stmt;
    }

    public function create()
    {
        // запрос для вставки (создания) записей
        $query = "INSERT INTO
            " . $this->table_name . "
        SET
            name=:name, description=:description, created=:created, modified=:modified";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // очистка
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->created = htmlspecialchars(strip_tags($this->created));
        $this->modified = htmlspecialchars(strip_tags($this->modified));

        // привязка значений
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":created", $this->created);
        $stmt->bindParam(":modified", $this->modified);

        // выполняем запрос
        if ($stmt->execute())
        {
            return true;
        }
        return false;
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if ($stmt->execute())
        {
            return true;
        }
        return false;
    }
    public function update()
    {
        // запрос для обновления записи (товара)
        $query = "UPDATE
        " . $this->table_name . "
        SET";

        $params = array();
        if (!empty($this->name))
        {
            $query .= " name = :name,";
            $params[':name'] = $this->name;
        }
        if (!empty($this->description))
        {
            $query .= " description = :description,";
            $params[':description'] = $this->description;
        }
        if (!empty($this->modified))
        {
            $query .= " modified = :modified,";
            $params[':modified'] = $this->modified;
        }
        $query = rtrim($query, ',');
        $query .= " WHERE id = :id";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        $params[':id'] = $this->id;

        foreach ($params as $key => &$value)
        {
            $stmt->bindParam($key, $value);
        }

        // выполняем запрос
        if ($stmt->execute())
        {
            return true;
        }
        return false;
    }
}
