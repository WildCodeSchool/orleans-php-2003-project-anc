<?php


namespace App\Model;

class AdminManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'coin';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * @param string $table
     * @param string $where
     * @return array
     */
    public function countData(string $table, string $where = ''):array
    {
        $req = 'SELECT COUNT(id) as coin FROM ' . $table;

        if (!empty($where)) {
            $req .= $where;
        }
        return $this->pdo->query($req)->fetch();
    }
}
