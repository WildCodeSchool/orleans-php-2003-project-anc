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
use App\Verify\VerifyDate;
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

    /**
     * Handle event deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $eventManager = new EventManager();
        $eventManager->delete($id);
        header('Location:/admin/event/?success=Evénement supprimé avec succès!');
    }

    public function add()
    {
        $eventManager = new EventManager();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_FILES['image']['error'] !== 4) {
                $dates = [];
                $files = new VerifyFileUpload($_FILES);
                $destination = 'assets/images/events/';
                $upload = $files->fileControl(true);
                $data = array_map('trim', $_POST);
                $dates['start'] = $data['start_date'];
                $dates['end'] = $data['end_date'];
                $data = array_filter($data);
                $errors = $this->formControl($data);
                $date = new VerifyDate($dates);
                $errorsDate = $date->dateControl();

                if (empty($errors) && empty($errorsDate) && array_key_exists('image', $upload)) {
                    $data['image'] = $upload['image']['name'];
                    $files->uploadFile($upload['image']['tmp_name'], $destination, $upload['image']['name']);
                    $eventManager->add($data);
                    header('Location: /admin/event/?success=Évènement ajouté');
                }
            } else {
                header('Location: /Event/add/?danger=Une image est requise');
            }
        }

        return $this->twig->render('Admin/Add/event.html.twig', [
            'errors' => $errors ?? [],
            'errorsDate' => $errorsDate ?? [],
            'errors_files' => $upload ?? [],
        ]);
    }

    public function edit($id)
    {
        $eventManager = new EventManager();
        $events = $eventManager->selectOneEventById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_FILES['img']['error'] !== 4) {
                $dates = [];
                $files = new VerifyFileUpload($_FILES);
                $destination = 'assets/images/events/';
                $upload = $files->fileControl(true);
                $data = array_map('trim', $_POST);
                $data['id'] = $events['id'];
                $dates['start'] = $data['start_at'];
                $dates['end'] = $data['end_at'];
                $data = array_filter($data);
                $errors = $this->formControl($data);
                $date = new VerifyDate($dates);
                $errorsDate = $date->dateControl();

                if (empty($errors) && empty($errorsDate) && array_key_exists('img', $upload)) {
                    $data['img'] = $upload['img']['name'];
                    $files->uploadFile($upload['img']['tmp_name'], $destination, $upload['img']['name']);
                    $eventManager->edit($data);
                    header("Location: /event/edit/$id/?success=Évènement modifié");
                } else {
                    header("Location: /event/edit/$id/?danger=Une erreur est survenue");
                }
            } else {
                $dates = [];
                $data = array_map('trim', $_POST);
                $data['id'] = $events['id'];
                $dates['start'] = $data['start_at'];
                $dates['end'] = $data['end_at'];
                $data['img'] = $events['img'];
                $data = array_filter($data);
                $errors = $this->formControl($data);
                $date = new VerifyDate($dates);
                $errorsDate = $date->dateControl();

                if (empty($errors) && empty($errorsDate)) {
                    $eventManager->edit($data);
                    header("Location: /event/edit/$id/?success=Évènement modifié");
                }
            }
        }

        return $this->twig->render('Admin/editEvent.html.twig', [
            'events' => $events,
            'errors' => $errors ?? [],
            'errorsDate' => $errorsDate ?? [],
            'errors_files' => $upload ?? [],
        ]);
    }

    private function formControl($data): array
    {
        $errors = [];

        if (isset($data['name']) && strlen($data['name']) > 255) {
            $errors['length_name'] = "Le nom de l'évènement doit contenir 255 caractères au maximum";
        }

        if (!isset($data['name'])) {
            $errors['empty_name'] = "Le nom de l'évènement est requis";
        }

        if (!isset($data['start_at'])) {
            $errors['empty_start_at'] = "La date de début de l'évènement est requise";
        }
        return $errors;
    }
}
