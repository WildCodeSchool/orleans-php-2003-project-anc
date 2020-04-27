<?php

namespace App\Controller;

use App\Model\ExhibitionManager;

/**
 * Class ExhibitionController
 *
 */
class ExhibitionController extends AbstractController
{
    public function index()
    {
        //model
        $exhibitionManager = new ExhibitionManager();
        $exhibitions = $exhibitionManager->selectAll();

        //view
        return $this->twig->render('Exhibition/index.html.twig', ['exhibitions' => $exhibitions]);
    }

    public function edit(int $id): string
    {
        $exhibitionManager = new ExhibitionManager();
        $exhibition = $exhibitionManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $exhibition['subject'] = $_POST['subject'];
            $exhibition['detail'] = $_POST['detail'];
            $exhibition['image'] = $_POST['image'];
            $exhibitionManager->update($exhibition);
        }

        return $this->twig->render('Exhibition/edit.html.twig', ['exhibition' => $exhibition]);
    }
}
