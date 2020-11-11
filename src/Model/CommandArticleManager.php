<?php

namespace App\Model;

class CommandArticleManager extends AbstractManager
{
    const TABLE = 'command_article';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function insert(array $command): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (id_command, id_article, qty) VALUES (:id_command, :id_article, :qty)");
        $statement->bindValue('id_command', $command['id_command'], \PDO::PARAM_INT);
        $statement->bindValue('id_article', $command['id_article'], \PDO::PARAM_INT);
        $statement->bindValue('qty', $command['qty'], \PDO::PARAM_INT);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    public function getArticlesByCommand(int $id)
    {
        $statement = $this->pdo->prepare("SELECT id, article_id FROM " . self::TABLE ." WHERE command_id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

}