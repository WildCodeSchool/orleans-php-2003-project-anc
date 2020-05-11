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

    public function selectThreeNextEvent(): array
    {
        return $this->pdo->query('SELECT * FROM ' . $this->table . ' ORDER BY start_at ASC LIMIT 0, 3')->fetchAll();
    }

    public function selectEvent(): array
    {
        $cond = 'WHERE start_at >= now() ORDER BY start_at ASC';
        return $this->pdo->query('SELECT * FROM ' . $this->table . ' ' . $cond)->fetchAll();
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
}
