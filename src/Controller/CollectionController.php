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
use mysql_xdevapi\Result;

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

        $collectionManager = new CollectionManager();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $root = 'assets/images/collection/';
            $files = new VerifyFileUpload($_FILES);
            $result = $files->fileControl(true);
            $data = array_map('trim', $_POST);
            $errors = $this->controlDataForm($data);

            if (empty($errors)) {
                if (array_key_exists('image-recto', $result) ||
                    array_key_exists('image-verso', $result) || empty($result)) {
                    if (isset($result['image-recto'])) {
                        $data['image_recto'] = $result['image-recto']['name'];
                        $files->uploadFile($result['image-recto']['tmp_name'], $root, $result['image-recto']['name']);
                    }
                    if (isset($result['image-verso'])) {
                        $data['image_verso'] = $result['image-verso']['name'];
                        $files->uploadFile($result['image-verso']['tmp_name'], $root, $result['image-verso']['name']);
                    }
                    $collectionManager->update((int) $id, $data);
                    header('Location: /admin/collection/?success=Données mises à jour avec succès !!');
                }
            }
        }


        $coin = $collectionManager->selectOneCoin((int)$id);
        $origins = $collectionManager->selectOrigin();
        $metals = $collectionManager->selectMetal();

        return $this->twig->render('Admin/editCollection.html.twig', [
            'coin' => $coin,
            'origins' => $origins,
            'metals' => $metals,
            'errors_files' => $result ?? [],
            'errors_form' => $errors ?? []
        ]);
    }

    private function controlDataForm($data): array
    {
        $errors = [];

        $convert = [
            'image-recto' => 'image recto',
            'image-verso' => 'image verso',
            'name' => 'nom de la pièce',
            'origin_id' => 'origine',
            'year' => 'année',
            'metal_id' => 'matériaux',
            'stock' => 'stock',
            'description' => 'description',
        ];

        foreach ($data as $name => $value) {
            if (empty($value) && $name !== 'stock') {
                $errors[] = 'Le champ ' . $convert[$name] . ' est requis';
            }
            if ($name !== 'description' && strlen($value) > 255) {
                $errors[] = 'Le champ ' . $convert[$name] . ' doit être inférieur à 255 caractères';
            }
        }
        return $errors;
    }
}
