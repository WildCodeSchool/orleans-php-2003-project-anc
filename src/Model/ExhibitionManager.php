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
        if (in_array('image', $data, true)) {
            $statement = $this->pdo->prepare("UPDATE " . self::TABLE
                . " SET `subject` = :subject, `detail` = :detail, `image` = :image WHERE id=:id");
        } else {
            $statement = $this->pdo->prepare("UPDATE " . self::TABLE
                . " SET `subject` = :subject, `detail` = :detail WHERE id=:id");
        }
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->bindValue(':subject', $data['subject'], \PDO::PARAM_STR);
        $statement->bindValue(':detail', $data['detail'], \PDO::PARAM_STR);
        if (in_array('image', $data, true)) {
            $statement->bindValue(':image', $data['image'], \PDO::PARAM_STR);
        }

        $statement->execute();
    }
}
