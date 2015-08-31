<?php


namespace controllers;


/**
 * This interface define all the basics action that must implements Controllers
 */
abstract class Controller
{

    /**
     * Check if the controller has the action
     * @param String $action action to verify
     * @return bool true if the controller has the action
     */
    abstract function hasAction($action);

    /**
     * Check if the current user has the right to go to the called action
     * @param String $action action to verify
     * @return bool true if the user has the right to access to the called action
     * @throws \ActionNotFoundException if the action doesn't exist
     */
    abstract function hasRightAccess($action);

    /**
     * Call the action to do when the user has no right access
     */
    function accessDenied(){
        return 'Access Denied';
    }

    /**
     * Initialise twig with his correct configuration
     * @return \Twig_Environment Twig initialised
     */
    function twigInit(){
        \Twig_Autoloader::register();

        $loader = new \Twig_Loader_Filesystem('../template');
        $twig = new \Twig_Environment($loader, array(
            'cache' => '../cache',
        ));
        return $twig;
    }
}