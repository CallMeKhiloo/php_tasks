<?php
class Database
{
    use bindIdTrait;
    private static $pdo = null;

    private function __construct() {}

    public static function connect($host, $user, $pass, $name)
    {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO("mysql:host=$host;dbname=$name", $user, $pass);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
        return self::$pdo;
    }

    public static function disconnect()
    {
        self::$pdo = null;
    }

    public static function insert($table, $data) // ['name' => 'John', 'email' => 'john@example.com']
    {
        $columns = implode(", ", array_keys($data)); // "name, email"
        $placeholders = ":" . implode(", :", array_keys($data)); // ":name, :email"
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        try {
            $stmt = self::$pdo->prepare($sql);
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Insert failed: " . $e->getMessage();
            return false;
        }
    }

    public static function select($table)
    {
        $sql = "SELECT * FROM $table";
        try {
            $stmt = self::$pdo->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                yield $row;
            }
        } catch (PDOException $e) {
            echo "Select failed: " . $e->getMessage();
            return false;
        }
    }

    public static function delete($table, $id)
    {
        $sql = "DELETE FROM $table WHERE id = :id";
        try {
            $stmt = self::$pdo->prepare($sql);
            self::bindId($stmt, $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Delete failed: " . $e->getMessage();
            return false;
        }
    }

    public static function update($table, $id, $data)
    {
        $set = "";
        foreach ($data as $key => $value) {
            $set .= "$key = :$key, "; // "name = :name, email = :email, "
        }
        $set = rtrim($set, ", "); // "name = :name, email = :email"
        $sql = "UPDATE $table SET $set WHERE id = :id";

        try {
            $stmt = self::$pdo->prepare($sql);
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            self::bindId($stmt, $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Update failed: " . $e->getMessage();
            return false;
        }
    }
}

trait bindIdTrait
{
    public static function bindId($stmt, $id)
    {
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    }
}
