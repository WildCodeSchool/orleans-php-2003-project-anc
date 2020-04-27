<?php


namespace App\Controller;

use App\Model\CollectionManager;
use App\Model\EventManager;
use App\Model\ExhibitionManager;
use App\Model\MessageManager;

class AdminController extends AbstractController
{
    public function index(): string
    {
        return $this->twig->render('Admin/index.html.twig');
    }


    public function event(): string
    {
        $eventManager = new EventManager();
        $events = $eventManager->selectEvent();

        return $this->twig->render('Admin/event.html.twig', ['events' => $events]);
    }

   /**
    * @return string
    * @throws \Twig\Error\LoaderError
    * @throws \Twig\Error\RuntimeError
    * @throws \Twig\Error\SyntaxError
    */
    public function exhibition(): string
    {
        $exhibitionManager = new ExhibitionManager();
        $exhibition = $exhibitionManager->selectExhibition();

        return $this->twig->render('Admin/exhibition.html.twig', ['exhibitions' => $exhibition]);
    }

   /**
    * @return string
    * @throws \Twig\Error\LoaderError
    * @throws \Twig\Error\RuntimeError
    * @throws \Twig\Error\SyntaxError
    */
    public function message(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $req = $this->remove($_POST['id']);
            if ($req === true) {
                header('Location: /admin/message/?success=Le message à bien été supprimé');
            } else {
                header('Location: /admin/message/?danger=Erreur inattendue, le message n\'a pas été supprimé');
            }
        }

        $messageManager = new MessageManager();
        $messages = $messageManager->selectAllMessages();

        return $this->twig->render('Admin/message.html.twig', ['messages' => $messages]);
    }

   /**
    * @return string
    * @throws \Twig\Error\LoaderError
    * @throws \Twig\Error\RuntimeError
    * @throws \Twig\Error\SyntaxError
    */
    public function collection(): string
    {
        $collectionManager = new CollectionManager();
        $collections = $collectionManager->selectAllCoins();

        return $this->twig->render('Admin/collection.html.twig', ['collections' => $collections]);
    }

   /**
    * @param int $id
    * @return bool
    */
    private function remove(int $id): bool
    {
        if (empty($id) || !is_numeric($id)) {
            return false;
        }

        $messageManager = new MessageManager();
        $messageManager->removeOneMessage($id);
        return true;
    }

   /**
    * @param string $id
    * @return string|null
    * @throws \Twig\Error\LoaderError
    * @throws \Twig\Error\RuntimeError
    * @throws \Twig\Error\SyntaxError
    */
    public function edit(string $id): ?string
    {
        if (!is_numeric($id)) {
            header('Location: /admin/collection');
            return null;
        }

        $collectionManager = new CollectionManager();

        $coin = $collectionManager->selectOneCoin((int)$id);
        $origins = $collectionManager->selectOrigin();
        $metals = $collectionManager->selectMetal();

        return $this->twig->render('Admin/edit.html.twig', [
         'coin' => $coin,
         'origins' => $origins,
         'metals' => $metals
        ]);
    }
}
