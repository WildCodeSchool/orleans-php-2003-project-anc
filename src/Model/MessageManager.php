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
    public function selectAllMessages()
    {
        return $this->pdo->query('SELECT * FROM ' . $this->table . ' ORDER BY send_at ASC')->fetchAll();
    }
}
