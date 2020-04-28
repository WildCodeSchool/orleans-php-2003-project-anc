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
use App\Verify\VerifyFileUpload;

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
    public function index(): string
    {
        $collectionManager = new CollectionManager();
        $coins = $collectionManager->selectAllCoins();

        return $this->twig->render('Collection/index.html.twig', ['coins' => $coins]);
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $files = new VerifyFileUpload($_FILES);
            $errors = $files->getIsArrayEmpty(true);
        }

        $collectionManager = new CollectionManager();

        $coin = $collectionManager->selectOneCoin((int)$id);
        $origins = $collectionManager->selectOrigin();
        $metals = $collectionManager->selectMetal();

        return $this->twig->render('Admin/editCollection.html.twig', [
            'coin' => $coin,
            'origins' => $origins,
            'metals' => $metals,
            'error' => $errors ?? []
        ]);
    }
}
