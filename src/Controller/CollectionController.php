<?php
/**
 * Created by PhpStorm.
<<<<<<< HEAD
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
=======
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
>>>>>>> dev
 */

namespace App\Controller;

use App\Model\CollectionManager;

/**
 * Class CollectionController
 *
 */

class CollectionController extends AbstractController
{

    /**
     * Display coin collection
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $collectionManager = new CollectionManager();
        $coins = $collectionManager->selectAllCoins();

        return $this->twig->render('Collection/index.html.twig', ['coins' => $coins]);
    }
}
