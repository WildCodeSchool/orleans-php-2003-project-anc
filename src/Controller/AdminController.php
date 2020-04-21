<?php


namespace App\Controller;

class AdminController extends AbstractController
{
    public function index()
    {
        return $this->twig->render('Admin/index.html.twig');
    }

    public function message()
    {
        return $this->twig->render('Admin/message.html.twig');
    }
}
