<?php


namespace App\Controller;

use App\Model\MessageManager;

class AdminController extends AbstractController
{
    public function index()
    {
        return $this->twig->render('Admin/index.html.twig');
    }

    public function message()
    {
        $messageManager = new MessageManager();
        $messages = $messageManager->selectAllMessages();

        return $this->twig->render('Admin/message.html.twig', ['messages' => $messages]);
    }
}
