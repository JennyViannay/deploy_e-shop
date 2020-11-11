<?php

namespace App\Model;

class UserCommandManager extends AbstractManager
{
    const TABLE = 'user_command';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function insert(array $command): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (id_command, id_user) VALUES (:id_command, :id_user)");
        $statement->bindValue('id_command', $command['id_command'], \PDO::PARAM_INT);
        $statement->bindValue('id_user', $command['id_user'], \PDO::PARAM_INT);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    public function getcommandlistByUser(int $id)
    {
        $statement = $this->pdo->prepare("SELECT id, command_id FROM " . self::TABLE ." WHERE user_id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

}