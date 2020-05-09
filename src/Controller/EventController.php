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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $destination = 'assets/images/events/';
            $files = new VerifyFileUpload($_FILES);
            $data = array_map('trim', $_POST);
            $upload = $files->fileControl(true);

            if (empty($errors)) {
                if (!empty($upload['img'])) {
                    $data['img'] = $upload['img']['name'];
                    $files->uploadFile($upload['img']['tmp_name'], $destination, $upload['img']['name']);
                }
                header('Location: /admin/event/?success=Évènement ajouté');
            }
        }

        return $this->twig->render('Admin/Add/event.html.twig', ['errors' => $errors ?? []
        ]);
    }
}
