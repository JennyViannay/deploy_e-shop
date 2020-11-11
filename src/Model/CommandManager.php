<?php

namespace App\Model;

class CommandManager extends AbstractManager
{
    const TABLE = 'command';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function insert(array $data): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (user_id, address, total, created_at) VALUES (:user_id, :address, :total, :created_at)");
        $statement->bindValue('user_id', $data['user_id'], \PDO::PARAM_INT);
        $statement->bindValue('address', $data['address'], \PDO::PARAM_STR);
        $statement->bindValue('total', $data['total'], \PDO::PARAM_INT);
        $statement->bindValue('created_at', $data['date']);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

}
