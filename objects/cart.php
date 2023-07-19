<?php


class Cart
{
    private $conn;
    private $table_name = "cart";

    public $id;
    public $user_id;
    public $product_id;
    public $amount;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function create()
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

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function read(){

        $query = "SELECT * FROM " . $this->table_name;

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // выполняем запрос
        $stmt->execute();
        return $stmt;
    }

    function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function update()
    {
        $query = "UPDATE
        " . $this->table_name . "
    SET";

        $params = array();

        if (!empty($this->amount)) {
            $query .= " amount = :amount,";
            $params[':amount'] = $this->amount;
        }
        $query = rtrim($query, ',');
        $query .= " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $params[':id'] = $this->id;

        foreach ($params as $key => &$value) {
            $stmt->bindParam($key, $value);
        }

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function read_user_cart($user_id){
        $query = "SELECT id, user_id, product_id, amount FROM " . $this->table_name . " WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":user_id", $user_id);

        $stmt->execute();

        return $stmt;
    }

}