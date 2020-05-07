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

    public function edit(string $id): ?string
    {
        if (!is_numeric($id)) {
            header('Location: /admin/event');
            return null;
        }
        $eventManager = new EventManager();
        $events = $eventManager->selectEvent();

        return $this->twig->render('/Admin/editEvent.html.twig', ['events' => $events]);
    }
}
