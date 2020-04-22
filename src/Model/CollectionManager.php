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
class CollectionManager extends AbstractManager
{
    /**
     *
     */
    const C_TABLE = 'coin';
    const M_TABLE = 'metal';
    const O_TABLE = 'origin';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::C_TABLE);
    }

    public function selectAllCoins(): array
    {
        $selectMetal = 'm.material, m.id';
        $selectOrigin = 'o.country, o.id';
        $selectCoin = 'c.name, c.year, c.image_recto, c.image_verso, c.stock, c.metal_id, c.origin_id';
        $selectAll = $selectCoin . ', ' . $selectMetal . ', ' . $selectOrigin;

        $joinMetal = 'JOIN ' . self::M_TABLE . ' m ON m.id=c.metal_id';
        $joinOrigin = 'JOIN ' . self::O_TABLE . ' o ON c.origin_id=o.id';
        $joinAll = $joinMetal . ' ' . $joinOrigin;

        $coinQuery = $this->pdo->query('SELECT ' . $selectAll . ' FROM ' . $this->table . ' c ' . $joinAll);
        return $coinQuery->fetchAll();
    }
}
