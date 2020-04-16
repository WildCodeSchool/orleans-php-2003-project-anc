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
        $error=null;
        $success=null;
        if (isset($_SESSION['error'])) {
            $error=$_SESSION['error'];
            unset($_SESSION['error']);
        } elseif (isset($_SESSION['success'])) {
            $success=$_SESSION['success'];
            unset($_SESSION['success']);
        }

        return $this->twig->render('Contact/index.html.twig', [
            'error' => $error,
            'success' => $success
        ]);
    }

    public function send()
    {

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);

            if (empty($data['firstname'])) {
                $errors[] = 'Vous devez renseigner votre prénom';
            } elseif (strlen($data['firstname']) > 100) {
                $errors[] = 'Votre prénom est trop long';
            }
            if (empty($data['lastname'])) {
                $errors[] = 'Vous devez renseigner votre prénom';
            } elseif (strlen($data['lastname']) > 100) {
                $errors[] = 'Votre nom de famille est trop long';
            }
            if (empty($data['email'])) {
                $errors[] = 'Vous devez renseigner votre email';
            } elseif (strlen($data['email']) > 100) {
                $errors[] = 'Votre email est trop long';
            } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Votre email est invalide';
            }
            if (empty($data['phone'])) {
                $errors[] = 'Vous devez renseigner votre numéro de téléphone';
            } elseif (!is_numeric($data['phone'])) {
                $errors[] = 'Votre numéro de téléphone est invalide';
            }
            if (empty($data['topic'])) {
                $errors[] = 'Vous devez renseigner votre sujet';
            } elseif (strlen($data['topic']) > 100) {
                $errors[] = 'Votre sujet est invalide';
            }
            if (empty($data['comment'])) {
                $errors[] = 'Commentaire est vide';
            }
            if (!isset($data['policy'])) {
                $errors[] = 'Veuillez acceptez la police de confidentialité';
            }
            if (empty($errors)) {
                $contactManager = new contactManager();
                $contactManager->insert($data);
                $_SESSION['success'] = 'Votre message a bien été envoyé';
                header('Location:/contact/index');
            } else {
                $_SESSION['error'] = $errors;
                header('Location:/contact/index');
            }
        }
        header('Location:/contact/index');
    }
}
