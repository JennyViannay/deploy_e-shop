<?php

namespace App\Model;

class ImageManager extends AbstractManager
{
    const TABLE = 'image';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectAll(): array
    {
        return $this->pdo->query("SELECT image.id, image.url, image.article_id, article.model as article_model FROM image
        JOIN article ON article.id = image.article_id")->fetchAll();
    }

    public function insert(array $data): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (url, article_id) VALUES (:url, :article_id)");
        $statement->bindValue('url', $data['url'], \PDO::PARAM_STR);
        $statement->bindValue('article_id', $data['article_id'], \PDO::PARAM_INT);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    public function update(array $data):bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET url=:url WHERE id=:id");
        $statement->bindValue('id', $data['id'], \PDO::PARAM_INT);
        $statement->bindValue('url', $data['url'], \PDO::PARAM_STR);

        return $statement->execute();
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function selectByArticle(int $id): array
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " WHERE article_id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}