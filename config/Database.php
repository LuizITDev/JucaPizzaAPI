
<?php


class Database {
    private $host = 'localhost';
    private $db_name = 'jucapizzasdb';
    private $username = 'root';
    private $password = 'usbw';
    private $port = '3310';
 
    public $conn;
 
 
       public function getConnection(){

        $this->conn = null;

        try {

            $dsn = 'mysql:host=' . $this->host .
                   ';port=' . $this->port .
                   ';dbname=' . $this->db_name .
                   ';charset=utf8';

            $this->conn = new PDO(
                $dsn,
                $this->username,
                $this->password
            );

            $this->conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

        } catch (PDOException $e) {

            echo 'Erro de Conexão: ' . $e->getMessage();
        } catch (Throwable $e) {
            echo 'Erro genérico: ' . $e->getMessage();
        }
        return $this->conn;
    }
}
 