<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 15:38
 * PHP version 7
 */

namespace App\Controller;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 *
 */
abstract class AbstractController
{
    /**
     * @var Environment
     */
    protected $twig;


    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        $loader = new FilesystemLoader(APP_VIEW_PATH);

        $this->twig = new Environment(
            $loader,
            [
                'cache' => !APP_DEV,
                'debug' => APP_DEV,
            ]
        );
        $this->twig->addGlobal('_get', $_GET);
        $this->twig->addGlobal('_post', $_POST);

        $this->twig->addExtension(new DebugExtension());
    }

    /**
     * @param array $files
     * @return bool
     */
    public function controlFiles(array $files): bool
    {
        $upload = true;
        $extensions = ['image/png', 'image/jpeg', 'image/jpg'];
        $sizeMax = 1000000;

        if ($files['error'] >= 1) {
            $upload = false;
        }

        if (!in_array($files['type'], $extensions, true)) {
            $upload = false;
        }

        if ($files['size'] > $sizeMax) {
            $upload = false;
        }
        return $upload;
    }
}
