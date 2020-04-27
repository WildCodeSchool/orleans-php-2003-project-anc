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

    public function update(string $id): void
    {
        $errors = false;
        $rootPath = 'assets/images/collection/';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/collection');
        }

        $post = array_map('trim', $_POST);
        foreach ($post as $i) {
            if ($i === '') {
                $errors = true;
            }
        }

        if (!$errors) {
            if ($_FILES['image-recto']['error'] !== 4) {
                $imageRecto = $this->controlFiles($_FILES['image-recto']);
                if ($imageRecto === true) {
                    $name = $post['name'];
                    $type = $_FILES['image-recto']['type'];
                    $imageNameRecto = $this->createNameImage($name, $type, 'recto');
                    $post['image_recto'] = $imageNameRecto;
                    move_uploaded_file($_FILES['image-recto']['tmp_name'], $rootPath . $imageNameRecto);
                }
            }
            if ($_FILES['image-verso']['error'] !== 4) {
                $imageVerso = $this->controlFiles($_FILES['image-verso']);
                if ($imageVerso === true) {
                    $name = $post['name'];
                    $type = $_FILES['image-verso']['type'];
                    $imageNameVerso = $this->createNameImage($name, $type, 'verso');
                    $post['image_verso'] = $imageNameVerso;
                    move_uploaded_file($_FILES['image-verso']['tmp_name'], $rootPath . $imageNameVerso);
                }
            }

            $collectionManager = new CollectionManager();
            $collectionManager->update((int)$id, $post);


            header('Location: /admin/collection/?success=Données mises à jour avec succès !');
        } else {
            header('Location: /admin/collection/?danger=Erreur inconnue - merci de rééssayer !');
        }
    }

    /**
     * @param string $image
     * @param string $type
     * @param string $face
     * @return string
     */
    private function createNameImage(string $image, string $type, string $face): ?string
    {
        $extension = trim(strrchr($type, '/'), '/');
        $imageName = str_replace(' ', '', $image) . '-' . $face;
        if (strlen($imageName . '.' . $extension) > 255) {
            return null;
        }
        return $imageName . '.' . $extension;
    }
}
