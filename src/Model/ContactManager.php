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
class ContactManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'contact';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $contact
     * @return bool
     */
    public function insert(array $contact): void
    {

        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " 
        (`firstname`,`lastname`, `email`, `phone`, `topic`, `comment`) 
        VALUES (:firstname, :lastname, :email, :phone, :topic, :comment)");
        $statement->bindValue('firstname', $contact['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $contact['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('email', $contact['email'], \PDO::PARAM_STR);
        $statement->bindValue('phone', $contact['phone'], \PDO::PARAM_INT);
        $statement->bindValue('topic', $contact['topic'], \PDO::PARAM_STR);
        $statement->bindValue('comment', $contact['comment'], \PDO::PARAM_STR);
        $statement->execute();
    }
}
