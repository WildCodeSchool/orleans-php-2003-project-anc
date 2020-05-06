<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

class ExhibitionManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'exhibition';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectExhibition()
    {
        return $this->pdo->query('SELECT * FROM ' . $this->table . ' ORDER BY image')->fetchAll();
    }

    public function update(int $id, array $data):void
    {
        if (array_key_exists('image', $data)) {
            $statement = $this->pdo->prepare("UPDATE " . self::TABLE
                . " SET `title` = :title, `detail` = :detail, `image` = :image WHERE id=:id");
        } else {
            $statement = $this->pdo->prepare("UPDATE " . self::TABLE
                . " SET `title` = :title, `detail` = :detail WHERE id=:id");
        }
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->bindValue(':title', $data['title'], \PDO::PARAM_STR);
        $statement->bindValue(':detail', $data['detail'], \PDO::PARAM_STR);
        if (array_key_exists('image', $data)) {
            $statement->bindValue(':image', $data['image'], \PDO::PARAM_STR);
        }

        $statement->execute();
    }

    public function add(array $data):void
    {
        if (array_key_exists('image', $data)) {
            $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE
                . " (title, detail, image) VALUES (:title, :detail, :image)");
        } else {
            $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE
                . " (title, detail) VALUES (:title, :detail)");
        }
        $statement->bindValue(':title', $data['title'], \PDO::PARAM_STR);
        $statement->bindValue(':detail', $data['detail'], \PDO::PARAM_STR);
        if (array_key_exists('image', $data)) {
            $statement->bindValue(':image', $data['image'], \PDO::PARAM_STR);
        }

        $statement->execute();
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
