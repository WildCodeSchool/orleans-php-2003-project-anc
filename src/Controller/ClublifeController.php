<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\ClublifeManager;
use App\Model\EventManager;
use App\Model\ExhibitionManager;
use App\Verify\VerifyFileUpload;

/**
 * Class contactController
 *
 */
class ClublifeController extends AbstractController
{


    /**
     * Display contact listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $clublifeManager = new ClublifeManager();
        $clublifes = $clublifeManager->selectClublife();
        return $this->twig->render('Clublife/index.html.twig', ['clublifes' => $clublifes]);
    }

    public function update(): array
    {
        $errors = [];
        $data = array_map('trim', $_POST);
        $errors = $this->controlData($data);

        if (empty($errors)) {
            $clublifeManager = new clublifeManager();
            $clublifeManager->update($data);
            foreach ($_FILES as $name => $image) {
                if ($image['error'] != 4) {
                    $image['position'] = $name;
                    $destination = 'assets/images/exhibition/';
                    $files = new VerifyFileUpload($_FILES);
                    $data = array_map('trim', $image);
                    $upload = $files->fileControl(true);
                    if (empty($errors)) {
                        if (!empty($upload['image'])) {
                            $data['image'] = $upload['image']['name'];
                            $files->uploadFile($upload['image']['tmp_name'], $destination, $upload['image']['name']);
                        }
                        $clublifeManager->updateimg($data);
                    }
                }
            }
            header('Location: /Admin/clublife/?success=Vos modifications ont été pris en compte!');
        }

        return $errors;
    }

    private function controlData($data): array
    {
        $errors = [];

        foreach ($data as $name => $value) {
            $convert = [
                'description_title' => 'titre de la description',
                'description' => 'description',
                'activity_title' => 'titre des activités',
                'activity' => 'activités'
            ];

            if (empty($value)) {
                $errors[] = 'Le champ ' . $convert[$name] . ' est requis';
            }
            if ($name == 'description_title' && strlen($value) > 100) {
                $errors[] = 'Le champ ' . $convert[$name] . ' doit être inférieur à 100 caractères';
            }
            if ($name == 'activity_title' && strlen($value) > 100) {
                $errors[] = 'Le champ ' . $convert[$name] . ' doit être inférieur à 100 caractères';
            }
        }
        return $errors;
    }
}
