<?php

namespace App\Database;

use App\Config\ConfigInterface;
use App\Database\DatabaseInterface;
use PDO;
use PDOException;


class Database implements DatabaseInterface
{
    private PDO $pdo;


    public function __construct(private ConfigInterface $config)
    {
        $this->connect();
    }

    public function exists(string $table, array $conditions = []): array|bool{
        $where = '';

        if (count($conditions) > 0) {
            $where = 'WHERE '.implode(' AND ', array_map(fn ($field) => "$field = :$field", array_keys($conditions)));
        }

        $sql = "SELECT * FROM $table $where LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($conditions);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result;
    }

    public function first(string $table, array $conditions = [],array $select = ['*']): array|false
    {
        $where = '';

        if (count($conditions) > 0) {
            $where = 'WHERE '.implode(' AND ', array_map(fn ($field) => "$field = :$field", array_keys($conditions)));
        }

        $select = implode(',', $select);

        $sql = "SELECT $select FROM $table $where LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($conditions);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ?: false;
    }

    public function select(string $table, array $conditions = [], array $order = [], int $limit = -1,
                           array $select = ['*'], int $offset = 0, string $selectionOperator = 'AND', $comparisonOperator = '=' ): array|false
    {
        $where = '';

        if (count($conditions) > 0) {
            if ($comparisonOperator == 'LIKE') {
                $where = 'WHERE ' . implode(" $selectionOperator ", array_map(fn($field) => "$field LIKE :$field", array_keys($conditions)));
            } else {
                $where = 'WHERE ' . implode(" $selectionOperator ", array_map(fn($field) => "$field = :$field", array_keys($conditions)));

            }
        }

        $select = implode(',', $select);

        $sql = "SELECT $select FROM $table $where ";

        if (count($order) > 0) {
            $sql .= ' ORDER BY '.implode(', ', array_map(fn ($field, $direction) => "$field $direction", array_keys($order), $order));
        }

        if ($limit > 0) {
            $sql .= " LIMIT $limit";
        }

        if($offset > 0){
            $sql .= ' OFFSET ' .$offset;
        }

        $stmt = $this->pdo->prepare($sql);

        try {
            $stmt->execute($conditions);
        }
        catch (PDOException $e) {
            return false;
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function superSelect(string $table, array $conditions = [], array $order = [], int $limit = -1, array $select = ['*'],
                                int $offset = 0, string $selectionOperator = 'AND', $comparisonOperator = '='): array|false
    {
        $where = '';
        $whereParts = [];
        $params = [];

        if (count($conditions) > 0) {

            foreach ($conditions as $field => $value) {
                if ($field == 'dateField') {
                    // For a date range, assume $value is an array with 'start' and 'end' keys
                    $whereParts[] = "{$value['name']} BETWEEN :{$value['name']}_start AND :{$value['name']}_end";
                    $params["{$value['name']}_start"] = $value['start'];
                    $params["{$value['name']}_end"] = $value['end'];
                } elseif (is_array($value) AND count($value) > 0) {
                    // For the IN clause
                    $placeholders = array_map(fn($key) => ":{$field}_{$key}", array_keys($value));
                    $whereParts[] = "$field IN (" . implode(', ', $placeholders) . ")";
                    foreach ($value as $key => $val) {
                        $params["{$field}_{$key}"] = $val;
                    }
                } else {
                    // For normal conditions
                    if ($comparisonOperator == 'LIKE') {
                        $whereParts[] = "$field LIKE :$field";;
                    } else {
                        $whereParts[] = "$field = :$field";;

                    }
                    $params[$field] = $value;
                }
            }

            $where = 'WHERE ' . implode(" $selectionOperator ", $whereParts);

        }

        $select = implode(',', $select);

        $sql = "SELECT $select FROM $table $where";

        if (count($order) > 0) {
            $sql .= ' ORDER BY '.implode(', ', array_map(fn ($field, $direction) => "$field $direction", array_keys($order), $order));
        }

        if ($limit > 0) {
            $sql .= " LIMIT $limit";
        }

        if($offset > 0){
            $sql .= ' OFFSET ' .$offset;
        }

        $stmt = $this->pdo->prepare($sql);

        try {
            $stmt->execute($params);
        }
        catch (PDOException $e) {
            return false;
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function insert(string $table, array $data)
    {
        $fields = array_keys($data);
        $columns = implode(', ', $fields);
        $binds = implode(', ', array_map(fn ($field) => "'$field'", $data));

        $sql = "INSERT INTO $table ($columns) VALUES ($binds)";

        $stmt = $this->pdo->prepare($sql);

        try {
            $stmt->execute();
        }
        catch (PDOException $e) {
            return $e->getMessage();
        }

        return $this->pdo->lastInsertId();
    }

    public function update(string $table, array $data, array $conditions = []): bool
    {
        $fields = array_keys($data);

        $set = implode(', ', array_map(fn ($field) => "$field = :$field", $fields));

        $where = '';

        if (count($conditions) > 0) {
            $where = 'WHERE '.implode(' AND ', array_map(fn ($field) => "$field = :$field", array_keys($conditions)));
        }

        $sql = "UPDATE $table SET $set $where";

        $stmt = $this->pdo->prepare($sql);

        try {
            $stmt->execute(array_merge($data, $conditions));
        }
        catch (PDOException $e) {
            return false;
        }

        return true;
    }

    public function delete(string $table, array $conditions = []): bool
    {
        $where = '';

        if (count($conditions) > 0) {
            $where = 'WHERE '.implode(' AND ', array_map(fn ($field) => "$field = :$field", array_keys($conditions)));
        }

        $sql = "DELETE FROM $table $where";

        $stmt = $this->pdo->prepare($sql);

        try {
            $stmt->execute($conditions);
        }
        catch (PDOException $e) {
            return false;
        }

        return true;
    }

    private function connect(): void{
        $server = $this->config->get('database.server');
        $db = $this->config->get('database.database');
        $userName = $this->config->get('database.username');
        $password = $this->config->get('database.password');

        try {
            $this->pdo = new PDO("mysql:host=$server;dbname=$db", $userName, $password);
        }
        catch (PDOException $PDOException) {
            exit("DB Error: ".$PDOException->getMessage());
        }
    }

}