<?php


namespace App\Model;

class OptionManager extends AbstractManager
{
    const TABLE = 'contact';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * @param string $table
     * @param string $column
     * @param string $value
     */
    public function addingOption(string $table, string $column, string $value): void
    {
        $req = $this->pdo->prepare('INSERT INTO ' . $table . ' (' . $column . ') VALUES(:name)');
        $req->bindValue(':name', $value, \PDO::PARAM_STR);
        $req->execute();
    }

    public function controlIfDataExist(string $table, string $column, string $entity)
    {
        $req = $this->pdo->prepare('SELECT * FROM ' . $table . ' WHERE ' . $column . '=:entity');
        $req->bindValue(':entity', $entity, \PDO::PARAM_STR);
        $req->execute();
        return $req->fetch();
    }
}
