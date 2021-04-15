<?php

namespace App\Model;

class UserManager extends AbstractManager
{
    const TABLE = 'user';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectAll(): array
    {
        return $this->pdo->query("SELECT user.id, user.email, user.username, role.name as role_name FROM user
        JOIN role ON role.id = user.role_id ORDER BY email")->fetchAll();
    }

    public function search(string $email)
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " WHERE email=:email");
        $statement->bindValue('email', $email, \PDO::PARAM_STR);
        $statement->execute();
        $user = $statement->fetchObject();
        if ($user) {
            return $user;
        }
        return false;
    }

    public function insert(array $user): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
        " (email, username, password, role_id) VALUES (:email, :username, :password, :role_id)");
        $statement->bindValue('email', $user['email'], \PDO::PARAM_STR);
        $statement->bindValue('username', $user['username'], \PDO::PARAM_STR);
        $statement->bindValue('password', $user['password'], \PDO::PARAM_STR);
        $statement->bindValue('role_id', $user['role_id'], \PDO::PARAM_INT);

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

    public function update(array $user):bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE .
        " SET email=:email, username=:username WHERE id=:id");
        $statement->bindValue('id', $user['id'], \PDO::PARAM_INT);
        $statement->bindValue('email', $user['email'], \PDO::PARAM_STR);
        $statement->bindValue('username', $user['username'], \PDO::PARAM_STR);

        return $statement->execute();
    }

    public function updatePassword(int $id, $password):bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE .
        " SET password=:password WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->bindValue('password', $password, \PDO::PARAM_STR);

        return $statement->execute();
    }

    public function getRole(int $id)
    {
        $statement = $this->pdo->prepare("SELECT
        role.name as role_name 
        FROM " . self::TABLE ." 
        JOIN role ON role.id=user.role_id 
        WHERE user.id=:id"
        );
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $role = $statement->fetch();
        if ($role) {
            return $role;
        }
        return false;
    }
}
