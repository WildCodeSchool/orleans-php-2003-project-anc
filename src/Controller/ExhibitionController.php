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

        return $this->twig->render('Update/exhibition.html.twig', ['exhibition' => $exhibition]);
    }

}
