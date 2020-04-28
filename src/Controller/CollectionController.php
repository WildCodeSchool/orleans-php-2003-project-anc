<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
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

    public function add()
    {
        return $this->twig->render('Admin/Add/addCollection.html.twig');
    }
  
    /**
     * @param string $id
     * @return string|null
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(string $id): ?string
    {
        if (!is_numeric($id)) {
            header('Location: /admin/collection');
            return null;
        }

        $collectionManager = new CollectionManager();
  
        $coin = $collectionManager->selectOneCoin((int) $id);
        $origins = $collectionManager->selectOrigin();
        $metals = $collectionManager->selectMetal();

        return $this->twig->render('Admin/edit.html.twig', [
            'coin' => $coin,
            'origins' => $origins,
            'metals' => $metals
        ]);
    }
}
