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

    public function getOrdersByUser(int $id)
    {
        $result = [];
        $articleManager = new ArticleManager();

        $statement = $this->pdo->prepare("SELECT * FROM " . self::TABLE ." WHERE user_id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $orders = $statement->fetchAll();

        foreach ($orders as $order) {
            $statement = $this->pdo->prepare("SELECT id_article, qty FROM command_article WHERE id_command=:id");
            $statement->bindValue('id', $order['id'], \PDO::PARAM_INT);
            $statement->execute();
            $articles = $statement->fetchAll();

            if (count($articles) > 1) {
                foreach ($articles as $article) {
                    $art = $articleManager->selectOneById($article['id_article']);
                    $order['articles'][] = [
                        'article' => $art,
                        'qty' => intval($article['qty'])
                    ]; 
                }
                $result[] = $order;
            } elseif (count($articles) === 1) {
                $order['articles'][] = [
                    'article' => $articleManager->selectOneById($articles[0]['id_article']),
                    'qty' => intval($articles[0]['qty'])
                ];
                $result[] = $order;
            }
        }
        return $result;
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

}
