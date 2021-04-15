<?php

namespace App\Model;

class ColorManager extends AbstractManager
{
    const TABLE = 'color';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectAll(): array
    {
        return $this->pdo->query("SELECT * FROM ". self::TABLE . " ORDER BY name")->fetchAll();
    }

    public function insert(string $name): void
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (name, is_used) VALUES (:name, :is_used)");
        $statement->bindValue(':name', $name, \PDO::PARAM_STR);
        $statement->bindValue(':is_used', false, \PDO::PARAM_BOOL);
        $statement->execute();
    }

    public function update(array $brand): void
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET name = :name WHERE id = :id");
        $statement->bindValue(':id', $brand['id'], \PDO::PARAM_INT);
        $statement->bindValue(':name', $brand['name'], \PDO::PARAM_STR);
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