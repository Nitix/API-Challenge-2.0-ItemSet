<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 27/08/2015
 * Time: 18:29
 */

namespace Nitixx\Controllers;


class IndexController extends  Controller
{
    private static $action = ['index', 'error404', 'databaseDown'];

    /**
     * @inheritDoc
     */
    function hasAction($action)
    {
        return in_array($action, self::$action);
    }

    /**
     * @inheritDoc
     */
    function hasRightAccess($action)
    {
        return true;
    }

    public function indexAction()
    {
        $twig = $this->twigInit();
        $template = $twig->loadTemplate('home.html.twig');
        echo $template->render([]);
    }

    public function error404Action()
    {
        $twig = $this->twigInit();
        $template = $twig->loadTemplate('404.html.twig');
        echo $template->render([]);
    }

    public function databaseDownAction()
    {
        $twig = $this->twigInit();
        $template = $twig->loadTemplate('databaseDown.html.twig');
        echo $template->render([]);
    }

    public function riotAPIDownAction()
    {
        $twig = $this->twigInit();
        $template = $twig->loadTemplate('riotAPIDown.html.twig');
        echo $template->render([]);
    }
}