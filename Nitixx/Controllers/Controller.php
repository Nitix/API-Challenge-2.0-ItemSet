<?php


namespace Nitixx\Controllers;


/**
 * This interface define all the basics action that must implements Controllers
 */
abstract class Controller
{

    /**
     * Routing
     * @var array
     */
    private static $routing = [
        'index' => [
          'index' => [],
        ],
        'itemset' => [
            'index' => [],
            'upload' => [],
            'view' => [ 'id' => 'id'],
            'download' => [ 'id' => 'id'],
            'create' => [],
            'post' => [],
            'list' => [],
        ],
    ];

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
     * @throws ActionNotFoundException if the action doesn't exist
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
        $loader = new \Twig_Loader_Filesystem(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .  'template');
        $twig = new \Twig_Environment($loader, array(
            'cache' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .  'cache',
            'debug' => true
        ));

        //Call static method
        $function = new \Twig_SimpleFunction('static_method', function ($class, $method, $arguments) {
            return call_user_func_array($class.'::'.$method, $arguments);
        });
        $twig->addFunction($function);

        //Generate an url
        $generate = new \Twig_SimpleFunction('url', function ($controller="", $action = "", $arguments = []) {
            return Controller::generateURL($controller, $action, $arguments);
        });
        $twig->addFunction($generate);

        //Fix Riot HTML
        $close_tag= new \Twig_SimpleFunction('close_tag', function($html){ //Some items have incorrect html
            $html_new = $html;
            preg_match_all ( "#<(?!br)([a-z]+)( .*)?(?!/)>#iU", $html, $result1);
            preg_match_all ( "#</([a-z]+)>#iU", $html, $result2);
            $results_start = $result1[1];
            $results_end = $result2[1];
            foreach($results_start AS $startag){
                if(!in_array($startag, $results_end)){
                    $html_new = str_replace('<'.$startag.'>', '', $html_new);
                }
            }
            foreach($results_end AS $endtag){
                if(!in_array($endtag, $results_start)){
                    $html_new = str_replace('</'.$endtag.'>', '', $html_new);
                }
            }
            return $html_new;
        });
        $twig->addFunction($close_tag);
        return $twig;
    }

    /**
     * Generate A URL with a specified controller, action
     * @param       $controller
     * @param       $action
     * @param array $args
     *
     * @return string
     */
    public static function generateURL($controller="", $action = "", array $args = [])
    {
        $url = '/';
        if(!empty($controller)){
            $url .= $controller;
            if(!empty($action)){
                $url .=  '/' . $action;
                foreach(static::$routing[$controller][$action] as $element)
                {
                    if(!isset($args[$element])) {
                        break;
                    }
                    $url .= '/' . $args[$element];
                }
                $extra = array_diff_key($args, static::$routing[$controller][$action]);
                if ($extra && $query = http_build_query($extra, '', '&')) {
                    $url .= '?'.strtr($query, array('%2F' => '/'));
                }
            }
        }
        return $url;
    }

    public static function bindArgs($controller, $action, array $args = [])
    {
        $data = [];
        if($controller != '' && $action != '') {
            $i = 0;
            foreach (static::$routing[$controller][$action] as $entry) {
                $data[$entry] = $args[$i];
                $i++;
            }
            foreach ($_GET as $key => $value){
                if($key != 'c' && $key != 'a' && $key != 'args'){
                    $data[$key] = $value;
                }
            }
        }
        return $data;
    }
}