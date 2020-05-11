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
}
