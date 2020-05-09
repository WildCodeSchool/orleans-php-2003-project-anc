<?php


namespace App\Controller;

use App\Model\ClublifeManager;
use App\Model\CollectionManager;
use App\Model\EventManager;
use App\Model\ExhibitionManager;
use App\Model\MessageManager;
use App\Model\OptionManager;
use App\Verify\VerifyFileUpload;

class AdminController extends AbstractController
{
    public function index(): string
    {
        return $this->twig->render('Admin/index.html.twig');
    }

    public function clublife(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clublifeController = new ClublifeController();
            $clublifeController->update();
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

    public function option(): string
    {
        $arr = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $arr = $this->addOption($_POST);
            if (empty($arr)) {
                $optionManager = new OptionManager();
                $optionManager->addingOption($_POST['table'], $_POST['column'], $_POST['new']);
                header('Location: /admin/option/?success=Enregistré avec succès !');
            } else {
                header('Location: /admin/option/?danger=' . $arr[0]);
            }
        }
        $collectionManager = new CollectionManager();
        $origin = $collectionManager->selectOrigin();
        return $this->twig->render('Admin/option.html.twig', [
            'origins' => $origin
        ]);
    }

    private function addOption(array $data): array
    {
        $errNew = [];
        $adding = array_map('trim', $data);
        foreach ($adding as $key) {
            if (empty($key)) {
                $errNew[] = 'Le champs est requis !';
            }
            if (strlen($key) > 80) {
                $errNew[] = 'Le champs doit-être inférieur à 80 caractères !';
            }
        }
        $optionManager = new OptionManager();
        $exist = $optionManager->controlIfDataExist($_POST['table'], $_POST['column'], $_POST['new']);
        if (!empty($exist)) {
            $errNew[] = 'La valeur existe déjà dans la liste !';
        }
        return $errNew;
    }
}
