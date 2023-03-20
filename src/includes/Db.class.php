<?php

require $_SERVER["DOCUMENT_ROOT"] . "/includes/options.env.php";

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
}
