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

    /**
     * @param string $table
     * @param string $column
     * @param string $entity
     * @return mixed
     */
    public function controlIfDataExist(string $table, string $column, string $entity)
    {
        $req = $this->pdo->prepare('SELECT * FROM ' . $table . ' WHERE ' . $column . '=:entity');
        $req->bindValue(':entity', $entity, \PDO::PARAM_STR);
        $req->execute();
        return $req->fetch();
    }
  
    /**
     * @param string $table
     * @param string $column
     * @param array  $data
     */
    public function updateOption(string $table, string $column, array $data): void
    {
        foreach ($data as $key => $value) {
            $req = $this->pdo->prepare('UPDATE ' . $table . ' SET ' . $column . '=:data WHERE id=:id');
            $req->bindValue(':data', $value, \PDO::PARAM_STR);
            $req->bindValue(':id', $key, \PDO::PARAM_INT);
            $req->execute();
        }
    }
}
