<?php

namespace Application\Models;

class Cart
{
    private $conn;
    private string $table_name = "cart";

    public int $id;
    public int $user_id;
    public int $product_id;
    public int $amount;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $query = "INSERT INTO
            " . $this->table_name . "
        SET
            user_id=:user_id, product_id=:product_id, amount=:amount";

        $stmt = $this->conn->prepare($query);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->amount = htmlspecialchars(strip_tags($this->amount));

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":amount", $this->amount);

        if ($stmt->execute())
        {
            return true;
        }
        return false;
    }

    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
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
        $query = "UPDATE
        " . $this->table_name . "
    SET";

        $params = array();
        if (!empty($this->amount))
        {
            $query .= " amount = :amount,";
            $params[':amount'] = $this->amount;
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

    public function read_user_cart(int $user_id)
    {
        $query = "SELECT id, user_id, product_id, amount FROM " . $this->table_name . " WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        return $stmt;
    }

}