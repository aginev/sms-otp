<?php

namespace SmsOtp;

class QueryBuilder
{
    protected \PDO $connection;
    
    protected string $table;
    
    public function getTable(): string
    {
        return $this->table;
    }
    
    protected function columnsSql(array $columns = []): string
    {
        if (!$columns) {
            $columns = ['*'];
        } else {
            array_map(function ($column) {
                return "`$column`";
            }, $columns);
        }
        
        return implode(',', $columns);
    }
    
    public function find($id, array $columns = [])
    {
        return $this->connection->query(
            "SELECT " . $this->columnsSql($columns) .
            " FROM `" . $this->table .
            "` WHERE `" . $this->keyName . "` = :id" .
            "LIMIT 1;",
            ['id' => $id]
        )->fetch(PDO::FETCH_ASSOC);
    }
    
    public function all(array $columns = [])
    {
        return $this->connection->query(
            "SELECT " . $this->columnsSql($columns) .
            " FROM `" . $this->table . "`;"
        )->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function insert(array $row)
    {
        $columnsSql = $this->columnsSql(array_keys($row));
        $valueBindings = implode(', ', array_fill(0, count($row), '?'));
        $values = array_values($row);
        
        $query = "INSERT INTO `" . $this->table . "` (" . $columnsSql . ") VALUES (" . $valueBindings . ");";
        
        return $this->connection->query($query, $values)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function update($id, array $row)
    {
        $columnsSql = implode('= ?, ', array_keys($row)) . ' = ?';
        $values = array_values($row);
        $values[] = $id;
        
        $query = "UPDATE `" . $this->table . "` SET " . $columnsSql . " WHERE `" . $this->keyName . "` = ? LIMIT 1;";
        
        return $this->connection->query($query, $values)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function delete($id)
    {
        return $this->connection->query(
            "DELETE FROM `" . $this->table . "` WHERE `" . $this->keyName . "` = :id LIMIT 1;",
            ['id' => $id]
        )->fetchAll(PDO::FETCH_ASSOC);
    }
}
