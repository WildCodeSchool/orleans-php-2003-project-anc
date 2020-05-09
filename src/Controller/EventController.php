<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\EventManager;
use App\Verify\VerifyFileUpload;

/**
 * Class contactController
 *
 */
class EventController extends AbstractController
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
        $eventManager = new EventManager();
        $events = $eventManager->selectEvent();

        return $this->twig->render('Event/index.html.twig', ['events' => $events]);
    }

    public function add()
    {
        $eventManager = new EventManager();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $destination = 'assets/images/events/';
            $files = new VerifyFileUpload($_FILES);
            $data = array_map('trim', $_POST);
            $errors = $this->formControl($data);
            $errorsDate = $this->dateControl($data);
            $upload = $files->fileControl(true);

            if (empty($errors) && empty($errorsDate)) {
                if (!empty($upload['image'])) {
                    $data['image'] = $upload['image']['name'];
                    $files->uploadFile($upload['image']['tmp_name'], $destination, $upload['image']['name']);
                }
                $eventManager->add($data);
                header('Location: /admin/event/?success=Évènement ajouté');
            }
        }

        return $this->twig->render('Admin/Add/event.html.twig', [
            'errors' => $errors ?? [],
            'errorsDate' => $errorsDate ?? [],
        ]);
    }

    private function formControl($data): array
    {
        $errors = [];

        if (strlen($data['name']) > 255) {
            $errors['length_name'] = 'Le nom de l\'évènement doit contenir 255 caractères au maximum';
        }

        if (empty($data['name'])) {
            $errors['empty_name']  = 'Le nom de l\'évènement est requis';
        }

        if (empty($data['start_date'])) {
            $errors['empty_start_date'] = 'La date de début de l\'évènement est requis';
        }

        if (empty($data['image'])) {
            $errors['empty_image'] = 'Une image est requise';
        }
        return $errors;
    }

    private function dateControl($data): array
    {
        $errorsDate = [];

        if ($data['start_date'] < date("Y-m-d")) {
            $errorsDate['early_start_date'] =
                "La date de début de l\'évènement doit être égale au minimum à la date du jour";
        }

        if ($data['end_date'] < date("Y-m-d")) {
            $errorsDate['early_end_date'] =
                "La date de fin de l\'évènement doit être égale au minimum à la date du jour";
        }

        if ($data['end_date'] <= $data['start_date']) {
            $errorsDate['end_date_before_start'] =
                "La date de fin de l\évènement doit se situer au minimum 1 jour après la date de début.
                 Si l'évènement se déroule sur une seule journée, saisir uniquement la date de début";
        }
        return $errorsDate;
    }
}
