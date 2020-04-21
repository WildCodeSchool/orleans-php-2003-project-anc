<?php


namespace App\Controller;

use App\Model\MessageManager;

class AdminController extends AbstractController
{
    public function index(): string
    {
        return $this->twig->render('Admin/index.html.twig');
    }

   /**
    * @return string
    * @throws \Twig\Error\LoaderError
    * @throws \Twig\Error\RuntimeError
    * @throws \Twig\Error\SyntaxError
    */
    public function message(): string
    {
//        $alert = [];
//        if ($_SERVER['REQUEST_METHOD'] === 'post' && isset($_POST['id'])) {
//            if ($this->remove($_POST['id'])) {
//                $alert['success'] = "Le message à bien été supprimé";
//            } else {
//                $alert['danger'] = "Suite à une erreur, le message n\a pas été supprimé";
//            }
//        }
        $messageManager = new MessageManager();
        $messages = $messageManager->selectAllMessages();

        return $this->twig->render('Admin/message.html.twig', [
           'messages' => $messages
           ]);
    }
}
