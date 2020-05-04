<?php


namespace App\Controller;

use App\Model\ClublifeManager;
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

    public function clublife(): string
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);
            $errors = $this->controlData($data);

            if (empty($errors)) {
                $clublifeManager = new clublifeManager();
                $clublifeManager->update($data);

                header('Location: /Admin/clublife/?success=Vos modifications ont été pris en compte!');
            }
        }

        $clublifeManager = new ClublifeManager();
        $clublifes = $clublifeManager->selectClublife();
        return $this->twig->render('Admin/clublife.html.twig', ['clublifes' => $clublifes]);
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

    private function controlData($data): array
    {
        $errors = [];

        foreach ($data as $name => $value) {
            $convert = [
                'description_title' => 'titre de la description',
                'description' => 'description',
                'activity_title' => 'titre des activités',
                'activity' => 'activités'
            ];

            if (empty($value)) {
                $errors[] = 'Le champ ' . $convert[$name] . ' est requis';
            }
            if ($name == 'description_title' && strlen($value) > 100) {
                $errors[] = 'Le champ ' . $convert[$name] . ' doit être inférieur à 100 caractères';
            }
            if ($name == 'activity_title' && strlen($value) > 100) {
                $errors[] = 'Le champ ' . $convert[$name] . ' doit être inférieur à 100 caractères';
            }
        }
        return $errors;
    }
}
