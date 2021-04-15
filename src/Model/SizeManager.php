<?php

namespace App\Model;

class SizeManager extends AbstractManager
{
    const TABLE = 'size';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectAll(): array
    {
        return $this->pdo->query("SELECT * FROM ". self::TABLE . " ORDER BY size")->fetchAll();
    }

    public function insert(string $size): void
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (size, is_used) VALUES (:size, :is_used)");
        $statement->bindValue(':size', $size, \PDO::PARAM_STR);
        $statement->bindValue(':is_used', false, \PDO::PARAM_BOOL);
        $statement->execute();
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function setIsUsed(int $id): void
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET is_used = 1 WHERE id = :id");
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}