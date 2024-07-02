<?php
namespace App\Controller;
require_once 'config.php';

class BaseController
{
    protected $db;

    public function __construct()
    {
        $this->initDatabase();
    }

    protected function initDatabase()
    {
        try {
            $this->db = new \PDO("mysql:host=". DB_HOST .";dbname=". DB_NAME, DB_USER, DB_PASS);
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }

    protected function store($table, $data)
    {
        $keys = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));

        $query = "INSERT INTO $table ($keys) VALUES ($values)";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array_values($data));

        return $this->db->lastInsertId();
    }

    protected function update($table, $data, $id)
    {
        $set = implode('=?, ', array_keys($data)) . '=?';

        $query = "UPDATE $table SET $set WHERE id=?";
        $stmt = $this->db->prepare($query);

        $values = array_values($data);
        $values[] = $id;

        $stmt->execute($values);

        return $stmt->rowCount();
    }

    protected function show($table, $id)
    {
        $query = "SELECT * FROM $table WHERE id=?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    protected function delete($table, $id)
    {
        $query = "DELETE FROM $table WHERE id=?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);

        return $stmt->rowCount();
    }

    protected function loadView($view, $data = [])
    {
        include_once 'views/' . $view . '.php';
    }

    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit();
    }

    protected function sanitizeInput($input)
    {
        return htmlspecialchars(trim($input));
    }
}
?>