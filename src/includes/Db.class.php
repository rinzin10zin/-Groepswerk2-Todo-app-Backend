<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require "./includes/options.env.php";

class Db
{

    private $pdo;

    // Create a new PDO instance
    public function __construct()
    {
        // Database configuration
        global $host;
        global $dbname;
        global $username;
        global $password;
        global $port;

        if ($this->pdo === null) {
            try {
                $this->pdo = new PDO(
                    "mysql:host=" . $host . ";port=" . $port . ";dbname=" . $dbname . ";charset=utf8mb4",
                    $username,
                    $password
                );
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
                return null;
            }
        }
    }

    public function executeQuery($sql, $data = [], $fetch = PDO::FETCH_OBJ)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return $stmt->fetchAll($fetch);
    }
    public function executeOneQuery($sql, $data = [], $fetch = PDO::FETCH_OBJ)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return $stmt->fetch($fetch);
    }
    public function executeInsertQuery($sql, $data = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute($data);
        if ($result) {
            // Get the ID of the last inserted row
            $last_insert_id = $this->pdo->lastInsertId();
            return $last_insert_id;
        } else {
            return false;
        }
    }
    public function executeDeleteQuery($sql, $data = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute($data);
        if ($result && $stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function executePatchQuery($sql, $data = [])
    {


        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute($data);

        if ($result && $stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function executeUpdateQuery($sql, $data = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return $stmt->rowCount();
    }
}
