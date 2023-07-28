<?php

namespace Application\Models;

/**
 * Class Product
 * @package Application\Models
 */
class Product
{
    // подключение к базе данных и таблице "products"
    /**
     * @var
     */
    private $conn;
    /**
     * @var string
     */
    private string $table_name = "products";

    // свойства объекта
    /**
     * @var int
     */
    public int $id;
    /**
     * @var string
     */
    public string $name;
    /**
     * @var string
     */
    public string $description;
    /**
     * @var int
     */
    public int $price;
    /**
     * @var int
     */
    public int $category_id;
    /**
     * @var string
     */
    public string $category_name;
    /**
     * @var string
     */
    public string $created;

    // конструктор для соединения с базой данных

    /**
     * Product constructor.
     * @param $db
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // метод для получения товаров

    /**
     * @return mixed
     */
    public function read()
    {
        // выбираем все записи
        $query = "SELECT
        c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
    FROM
        " . $this->table_name . " p
        LEFT JOIN
            categories c
                ON p.category_id = c.id
    ORDER BY
        p.created DESC";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // выполняем запрос
        $stmt->execute();
        return $stmt;
    }

    // метод для создания товаров

    /**
     * @return bool
     */
    public function create()
    {
        // запрос для вставки (создания) записей
        $query = "INSERT INTO
            " . $this->table_name . "
        SET
            name=:name, price=:price, description=:description, category_id=:category_id, created=:created";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // очистка
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->created = htmlspecialchars(strip_tags($this->created));

        // привязка значений
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":created", $this->created);

        // выполняем запрос
        if ($stmt->execute())
        {
            return true;
        }
        return false;
    }

    // метод для получения конкретного товара по ID

    /**
     * @param $id
     * @return mixed
     */
    public function read_one($id)
    {
        $query = "SELECT id, name, description, price, category_id, created, modified FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $id);

        $stmt->execute();

        return $stmt;
    }

    // метод для обновления товара

    /**
     * @return bool
     */
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
        if (!empty($this->price))
        {
            $query .= " price = :price,";
            $params[':price'] = $this->price;
        }
        if (!empty($this->description))
        {
            $query .= " description = :description,";
            $params[':description'] = $this->description;
        }
        if (!empty($this->category_id))
        {
            $query .= " category_id = :category_id,";
            $params[':category_id'] = $this->category_id;
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

    /**
     * @return bool
     */
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

    // метод для поиска товаров

    /**
     * @param $keywords
     * @return mixed
     */
    public function search($keywords)
    {
        // поиск записей (товаров) по "названию товара", "описанию товара", "названию категории"
        $query = "SELECT
            c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
        FROM
            " . $this->table_name . " p
            LEFT JOIN
                categories c
                    ON p.category_id = c.id
        WHERE
            p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?
        ORDER BY
            p.created DESC";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // очистка
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        // привязка
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);

        // выполняем запрос
        $stmt->execute();

        return $stmt;
    }
}