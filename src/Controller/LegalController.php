<?php


namespace App\Controller;

class LegalController extends AbstractController
{

   /**
    * @return string
    * @throws \Twig\Error\LoaderError
    * @throws \Twig\Error\RuntimeError
    * @throws \Twig\Error\SyntaxError
    */
    public function privacyPolicy(): string
    {
        return $this->twig->render('Legal/privacyPolicy.html.twig');
    }
}
