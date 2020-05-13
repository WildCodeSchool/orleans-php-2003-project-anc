<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

/**
 *
 */
class EventManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'event';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectOneEventById(int $id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE event.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function selectThreeNextEvent(): array
    {
        return $this->pdo->query('SELECT * FROM ' . $this->table . ' ORDER BY start_at ASC LIMIT 0, 3')->fetchAll();
    }

    public function selectFuturEvent(): array
    {
        $cond = 'WHERE start_at >= now() ORDER BY start_at ASC';
        return $this->pdo->query('SELECT * FROM ' . $this->table . ' ' . $cond)->fetchAll();
    }

    public function selectAllEvent(): array
    {
        return $this->pdo->query('SELECT * FROM ' . $this->table)->fetchAll();
    }
    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }


    public function add(array $data): void
    {
        if (array_key_exists('end_date', $data)) {
            $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE
                . " (name, start_at, end_at, img) VALUES (:name, :start_at, :end_at, :image)");
        } else {
            $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE
                . " (name, start_at, img) VALUES (:name, :start_at, :image)");
        }
        $statement->bindValue(':name', $data['name'], \PDO::PARAM_STR);
        $statement->bindValue(':start_at', $data['start_date'], \PDO::PARAM_STR);
        $statement->bindValue(':image', $data['image'], \PDO::PARAM_STR);
        if (array_key_exists('end_date', $data)) {
            $statement->bindValue(':end_at', $data['end_date'], \PDO::PARAM_STR);
        }

        $statement->execute();
    }


    public function edit(array $data): void
    {

        // prepared request

        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " 
            SET `name` = :name, `img` = :img, `start_at` = :start_at
            WHERE id = :id");
        $statement->bindValue('id', $data['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $data['name'], \PDO::PARAM_STR);
        $statement->bindValue('start_at', $data['start_at'], \PDO::PARAM_STR);
        $statement->bindValue('img', $data['img'], \PDO::PARAM_STR);

        $statement->execute();

        if (isset($data['end_at'])) {
            $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " 
            SET `end_at` = :end_at WHERE id = :id");
            $statement->bindValue('id', $data['id'], \PDO::PARAM_INT);
            $statement->bindValue('end_at', $data['end_at'], \PDO::PARAM_STR);

            $statement->execute();
        }
    }
}
