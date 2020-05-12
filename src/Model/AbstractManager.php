<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 20:52
 * PHP version 7
 */

namespace App\Model;

use App\Model\Connection;

/**
 * Abstract class handling default manager.
 */
abstract class AbstractManager
{
    /**
     * @var \PDO
     */
    protected $pdo; //variable de connexion

    /**
     * @var string
     */
    protected $table;
    /**
     * @var string
     */
    protected $className;


    /**
     * Initializes Manager Abstract class.
     * @param string $table
     */
    public function __construct(string $table)
    {
        $this->table = $table;
        $this->className = __NAMESPACE__ . '\\' . ucfirst($table);
        $this->pdo = (new Connection())->getPdoConnection();
    }

    /**
     * Get all row from database.
     *
     * @return array
     */
    public function selectAll(): array
    {
        return $this->pdo->query('SELECT * FROM ' . $this->table)->fetchAll();
    }

    /**
     * Get one row from database by ID.
     *
     * @param int $id
     *
     * @return array
     */
    public function selectOneById(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    /**
     * <p>This function checks whether the table passed as a parameter exists.</p>
     * @param string $table the table to be checked </p>
     * @return bool return true or false
     */
    public function controlTableExist(string $table): bool
    {
        $sql = $this->pdo->query('SHOW TABLES FROM anc LIKE "' . $table . '";');
        return $sql->rowCount() > 0;
    }

    /**
     * <p>This function checks whether in the column passed in parameter exists in the table passed in parameter.</p>
     * <p>The function will call <b>controlTableExist</b>
     * before executing the test on the column. </p>
     * @param string $table  the table containing the column to be checked
     * @param string $column the column to be checked
     * @return bool return true or false
     */
    public function controlColumnExist(string $table, string $column): bool
    {
        if ($this->controlTableExist($table)) {
            $sql = $this->pdo->query('SHOW COLUMNS FROM ' . $table . ' LIKE "' . $column . '";');
            return $sql->rowCount() > 0;
        }
        return false;
    }
}
