<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\ContactManager;

/**
 * Class contactController
 *
 */
class ContactController extends AbstractController
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
        $contactManager = new contactManager();
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);
            $errors = $this->controlData($data);

            if (empty($errors)) {
                $topic = $contactManager->selectSubject();
                for ($i = 0; $i < count($topic); $i++) {
                    if ($topic[$i]['id'] === $data['topic']) {
                        $data['topic'] = $topic[$i]['subject'];
                    }
                }
                $contactManager->insert($data);

                header('Location: /Contact/index/?success=Votre message a été envoyé !');
            }
        }
        $subject = $contactManager->selectSubject();

        return $this->twig->render('Contact/index.html.twig', [
            'error' => $errors,
            'subjects' => $subject
        ]);
    }

    public function send()
    {

        header('Location:/contact/index');
    }

    private function controlData($data): array
    {
        $errors = [];

        $convert = [
            'lastname' => 'nom',
            'firstname' => 'prénom',
            'comment' => 'commentaire',
            'phone' => 'téléphone',
            'email' => 'email',
            'topic' => 'sujet',
        ];

        foreach ($data as $name => $value) {
            if (empty($value)) {
                $errors[] = 'Le champ ' . $convert[$name] . ' est requis';
            }
            if ($name !== 'comment' && strlen($value) > 100) {
                $errors[] = 'Le champ ' . $convert[$name] . ' doit être inférieur à 100 caractères';
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Votre email est invalide';
        }
        if (!is_numeric($data['phone']) || strlen($data['phone']) !== 10) {
            $errors[] = 'Votre numéro de téléphone doit-être un nombre entier à 12 chiffres';
        }
        if (!isset($data['policy'])) {
            $errors[] = 'Veuillez acceptez la police de confidentialité';
        }

        return $errors;
    }
}
