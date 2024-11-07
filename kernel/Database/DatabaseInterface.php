<?php

namespace App\Database;

interface DatabaseInterface
{
    public function select(string $table, array $conditions = [], array $order = [], int $limit = -1, array $select = ['*'],
                           int $offset = 0, string $selectionOperator = 'AND',$comparisonOperator = '='): array|false;

    public function superSelect(string $table, array $conditions = [], array $order = [], int $limit = -1, array $select = ['*'],
                                int $offset = 0, string $selectionOperator = 'AND', $comparisonOperator = '='): array|false;

    public function insert(string $table, array $data);
    public function update(string $table, array $data, array $conditions): bool;
    public function delete(string $table): bool;
    public function first(string $table, array $conditions = []): array|false;
    public function exists(string $table, array $conditions = []): array|bool;
}