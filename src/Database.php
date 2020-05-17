<?php
namespace Src;
use PDO;

class Database
{
    protected $link;

    public function __construct() {
        try {
            $this->link = new PDO('mysql:host='.getenv('DB_HOST').';dbname='.getenv('DB_NAME').'', getenv('DB_USER'), getenv('DB_PASS'));
        } catch (\PDOException $e) {
            echo 'Сonnection failed'.$e->getMessage();
        }
    }

    public function insert($sql, array $params = []) {
        $statement = $this->_execute($sql, $params);
        return $this->link->lastInsertId();
    }

    public function update(array $params = [], $id) {
        $sql = $this->_buildUpdateSql($params, $id);
        $statement = $this->_execute($sql, $params);
        return $statement->rowCount();
    }

    public function select($sql) {
        $statement = $this->_execute($sql);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($sql) {
        $statement = $this->_execute($sql);
        return $statement->rowCount();
    }

    private function _execute($sql, array $params = []) {
        $statement = $this->link->prepare($sql);
        $statement->execute($params);
        return $statement;
    }

    private function _buildUpdateSql($data, $id) {
        $sql = 'UPDATE Users SET ';
        $fieldExpressions = [];
        foreach ($data as $field => $value) {
            $fieldExpressions[] = "{$field} = :{$field}";
        }
        $sql .= implode(', ', $fieldExpressions);
        $sql .= " WHERE id = {$id}";
        return $sql;
    }
}