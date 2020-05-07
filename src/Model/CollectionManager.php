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
        $select = $this->selectJoin();
        $coinQuery = $this->pdo->query($select);
        return $coinQuery->fetchAll();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function selectOneCoin(int $id)
    {
        $select = $this->selectJoin();
        $coinQuery = $this->pdo->prepare($select . ' WHERE c.id=:id');
        $coinQuery->bindValue(':id', $id, \PDO::PARAM_INT);
        $coinQuery->execute();
        return $coinQuery->fetch();
    }

    /**
     * @return string
     */
    private function selectJoin(): string
    {
        $selectMetal = 'm.material, m.id';
        $selectOrigin = 'o.country, o.id';
        $selectCoin = 'c.id as coin_id, c.name, c.year, c.image_recto,';
        $selectCoin .= ' c.image_verso, c.stock, c.metal_id, c.origin_id, c.description';
        $selectAll = $selectCoin . ', ' . $selectMetal . ', ' . $selectOrigin;

        $joinMetal = 'JOIN ' . self::M_TABLE . ' m ON c.metal_id=m.id';
        $joinOrigin = 'JOIN ' . self::O_TABLE . ' o ON c.origin_id=o.id';
        $joinAll = $joinMetal . ' ' . $joinOrigin;

        return 'SELECT ' . $selectAll . ' FROM ' . $this->table . ' c ' . $joinAll;
    }

    public function selectOrigin(): array
    {
        return $this->pdo->query('SELECT * FROM ' . self::O_TABLE)->fetchAll();
    }

    public function selectMetal(): array
    {
        return $this->pdo->query('SELECT * FROM ' . self::M_TABLE)->fetchAll();
    }

    public function update(int $id, array $data): void
    {
        $keys = array_keys($data);
        $str = '';
        foreach ($keys as $key) {
            $str .= $key . '=:' . $key . ',';
        }
        $str = rtrim($str, ',');

        $req = $this->pdo->prepare('UPDATE ' . self::C_TABLE . ' SET ' . $str . ' WHERE id=:id');
        $req->bindValue(':id', $id, \PDO::PARAM_INT);
        $req->bindValue(':name', $data['name'], \PDO::PARAM_STR);
        $req->bindValue(':description', $data['description'], \PDO::PARAM_STR);
        $req->bindValue(':year', $data['year'], \PDO::PARAM_INT);
        $req->bindValue(':metal_id', $data['metal_id'], \PDO::PARAM_INT);
        $req->bindValue(':origin_id', $data['origin_id'], \PDO::PARAM_INT);
        $req->bindValue(':stock', $data['stock'], \PDO::PARAM_INT);
        if (in_array('image_recto', $keys, true)) {
            $req->bindValue(':image_recto', $data['image_recto'], \PDO::PARAM_STR_CHAR);
        }
        if (in_array('image_verso', $keys, true)) {
            $req->bindValue(':image_verso', $data['image_verso'], \PDO::PARAM_STR_CHAR);
        }
        $req->execute();
    }

    /**
     * @param int $id
     */
    public function deleteOneCoin(int $id): void
    {
        $req = $this->pdo->prepare('DELETE FROM ' . self::C_TABLE . ' WHERE id=:id');
        $req->bindValue(':id', $id, \PDO::PARAM_INT);
        $req->execute();
    }

    /**
     * @param array $data
     */
    public function add(array $data): void
    {
        if (array_key_exists('image_recto', $data) && (!array_key_exists('image_verso', $data))) {
            $query = 'INSERT INTO ' . self::C_TABLE .
                ' (name, year, image_recto, stock, metal_id, origin_id, description) 
                VALUES (:name, :year, :image_recto, :stock, :metal_id, :origin_id, :description)';
        }
        if (array_key_exists('image_recto', $data) && (array_key_exists('image_verso', $data))) {
            $query = 'INSERT INTO ' . self::C_TABLE .
                ' (name, year, image_recto, image_verso, stock, metal_id, origin_id, description) 
                VALUES (:name, :year, :image_recto, :image_verso, :stock, :metal_id, :origin_id, :description)';
        } else {
            $query = 'INSERT INTO ' . self::C_TABLE .
                ' (name, year, stock, metal_id, origin_id, description) 
                VALUES (:name, :year, :stock, :metal_id, :origin_id, :description)';
        }
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':name', $data['name'], \PDO::PARAM_STR);
        $statement->bindValue(':year', $data['year'], \PDO::PARAM_INT);
        $statement->bindValue(':stock', $data['stock'], \PDO::PARAM_INT);
        $statement->bindValue(':metal_id', $data['metal_id'], \PDO::PARAM_INT);
        $statement->bindValue(':origin_id', $data['origin_id'], \PDO::PARAM_INT);
        $statement->bindValue(':description', $data['description'], \PDO::PARAM_STR);
        if (array_key_exists('image_recto', $data)) {
            $statement->bindValue(':image_recto', $data['image_recto'], \PDO::PARAM_STR);
        }
        if (array_key_exists('image_verso', $data)) {
            $statement->bindValue(':image_verso', $data['image_verso'], \PDO::PARAM_STR);
        }
        $statement->execute();
    }

    public function sort(array $data): void
    {

    }
}
