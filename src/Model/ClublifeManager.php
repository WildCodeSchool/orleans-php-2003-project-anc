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
class ClublifeManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'clublife';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectClublife(): array
    {
        return $this->pdo->query('SELECT * FROM ' . $this->table)->fetchAll();
    }

    public function update(array $clublife): void
    {
        // prepared request
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " 
        SET description_title =:description_title, description =:description,
         activity_title =:activity_title, activity =:activity
         WHERE id = 1");
        $statement->bindValue('description_title', $clublife['description_title'], \PDO::PARAM_STR);
        $statement->bindValue('description', $clublife['description'], \PDO::PARAM_STR);
        $statement->bindValue('activity_title', $clublife['activity_title'], \PDO::PARAM_STR);
        $statement->bindValue('activity', $clublife['activity'], \PDO::PARAM_STR);
        $statement->execute();
    }
}
