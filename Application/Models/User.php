<?php

namespace Application\Models;

/**
 * Class User
 * @package Application\Models
 */
class User
{
    /**
     * @var
     */
    private $conn;
    /**
     * @var string
     */
    private $table_name = "users";

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
    public string $email;
    /**
     * @var string
     */
    public string $password;
    /**
     * @var string
     */
    public string $registration_date;

    /**
     * User constructor.
     * @param $db
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * @return bool
     */
    public function create()
    {
        $query = "INSERT INTO
            " . $this->table_name . "
        SET
            name=:name, email=:email, password=:password, registration_date=:registration_date";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->registration_date = htmlspecialchars(strip_tags($this->registration_date));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":registration_date", $this->registration_date);

        if ($stmt->execute())
        {
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name;
        // подготовка запроса
        $stmt = $this->conn->prepare($query);
        // выполняем запрос
        $stmt->execute();
        return $stmt;
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

    /**
     * @return bool
     */
    public function update()
    {
        $query = "UPDATE
        " . $this->table_name . "
    SET";
        $params = array();
        if (!empty($this->name))
        {
            $query .= " name = :name,";
            $params[':name'] = $this->name;
        }
        if (!empty($this->email))
        {
            $query .= " email = :email,";
            $params[':email'] = $this->email;
        }
        if (!empty($this->password))
        {
            $query .= " password = :password,";
            $params[':password'] = $this->password;
        }

        $query = rtrim($query, ',');
        $query .= " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $params[':id'] = $this->id;

        foreach ($params as $key => &$value)
        {
            $stmt->bindParam($key, $value);
        }

        if ($stmt->execute())
        {
            return true;
        }
        return false;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function read_one($id)
    {
        $query = "SELECT id, name, email, password FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $id);

        $stmt->execute();

        return $stmt;
    }
}