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

    public function update(array $exhibition):bool
    {

        $sqlSet = " SET 'subject' = :subject, 'detail' = :detail, 'image' = :image WHERE id=:id";

        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . $sqlSet);
        $statement->bindValue('id', $exhibition['id'], \PDO::PARAM_INT);
        $statement->bindValue('subject', $exhibition['subject'], \PDO::PARAM_STR);
        $statement->bindValue('detail', $exhibition['detail'], \PDO::PARAM_STR);
        $statement->bindValue('image', $exhibition['image'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}
