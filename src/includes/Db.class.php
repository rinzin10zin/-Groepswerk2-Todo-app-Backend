<?php

class Db
{

    // Database configuration
    private $host = 'ID396978_phpFuncProgram.db.webhosting.be';
    private $dbname = 'ID396978_phpFuncProgram';
    private $username = 'ID396978_phpFuncProgram';
    private $password = 'rootroot!123';
    private $port = 3306;
    private $pdo;

    // Create a new PDO instance
    public function __construct()
    {
        if ($this->pdo === null) {
            try {
                $this->pdo = new PDO(
                    "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->dbname . ";charset=utf8mb4",
                    $this->username,
                    $this->password
                );
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
                return null;
            }
        }
    }

    public function executeGetQuery($sql, $fetch = PDO::FETCH_OBJ)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll($fetch);
    }
}
