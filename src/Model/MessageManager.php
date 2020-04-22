<?php


namespace App\Model;

class MessageManager extends AbstractManager
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
    * @return array
    */
    public function selectAllMessages(): array
    {
        return $this->pdo->query('SELECT * FROM ' . $this->table . ' ORDER BY send_at DESC')->fetchAll();
    }

   /**
    * @param $id
    */
    public function removeOneMessage($id):void
    {
        $this->pdo->exec('DELETE FROM ' . $this->table . ' WHERE id=' . $id . ' LIMIT 1');
    }
}
