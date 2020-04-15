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
        $contactManager = new ContactManager();
        $contacts = $contactManager->selectAll();

        return $this->twig->render('Contact/index.html.twig', ['contacts' => $contacts]);
    }


    /**
     * Display contact informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $contactManager = new contactManager();
        $contact = $contactManager->selectOneById($id);

        return $this->twig->render('contact/show.html.twig', ['contact' => $contact]);
    }


    /**
     * Display contact edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id): string
    {
        $contactManager = new contactManager();
        $contact = $contactManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contact['title'] = $_POST['title'];
            $contactManager->update($contact);
        }

        return $this->twig->render('contact/edit.html.twig', ['contact' => $contact]);
    }


    /**
     * Display contact creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contactManager = new contactManager();
            $contact = [
                'title' => $_POST['title'],
            ];
            $id = $contactManager->insert($contact);
            header('Location:/contact/show/' . $id);
        }

        return $this->twig->render('contact/add.html.twig');
    }


    /**
     * Handle contact deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $contactManager = new contactManager();
        $contactManager->delete($id);
        header('Location:/contact/index');
    }
}

