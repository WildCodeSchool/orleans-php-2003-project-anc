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

    public function selectThreeNextEvent()
    {
        return $this->pdo->query('SELECT * FROM ' . $this->table . ' ORDER BY start_at ASC LIMIT 0, 3')->fetchAll();
    }
}
