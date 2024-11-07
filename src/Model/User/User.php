<?php

namespace App\src\Model\User;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Database\Database;
use App\Database\DatabaseInterface;

class User
{
    private DatabaseInterface $database;
    private ConfigInterface $config;

    private int $id = 0;
    private string $full_name = '';
    private string $role = '';
    private string $efficiency = '';
    private string $error = '';

    public array $fields = [
        "id","full_name", "role", "efficiency"
    ];

    public function __construct(array $data = []){
        $this->config = new Config();
        $this->database = new Database($this->config);

        if(count($data) > 0){
            $user = $this->database->first('users',$data);

            if($user){
                foreach ($user as $key => $value) {
                    $this->$key = $value;
                }
            }
        }
    }

    public function edit(array $values): void
    {
        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }

    public function id(): int
    {
        return $this->id;
    }

    public function get(array $conditions = []): array
    {
        if (empty($conditions)) {
            $data = [];
            foreach ($this->fields as $field) {
                $data[$field] = $this->$field;
            }
            return $data;
        }

        $returnedArray = [];
        try {
            foreach ($conditions as  $value) {
                $returnedArray[$value] = $this->$value;
            }
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }

        return $returnedArray;
    }

    public function error()
    {
        return $this->error;
    }

    public function save(): bool
    {
        $data = [];
        foreach ($this->fields as $field) {
            $data[$field] = $this->$field;
        }
        if($this->id > 0){
            $stmt = $this->database->update(
                'users',
                $data,
                ['id' => $this->id],
            );
        }
        else{
            $stmt = $this->database->insert(
                'users',
                $data
            );

            if ($stmt)
                $this->id = $stmt;
        }

        return $stmt;
    }
}