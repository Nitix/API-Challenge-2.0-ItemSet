<?php


namespace Nitixx\Controllers;
use Nitixx\Utils\Utils;

/**
 * Fetch the correct Controller and call the action, it also verify the access right
 */
class Dispatcher
{

    /**
     * Default Controller to be called when the controller does not exist
     */
    const DEFAULT_CONTROLLER = 'IndexController';

    /**
     * Indicates with Controllers exists used to route querys
     * @var array list of Controllers (routes)
     */
    private $controllers = array(
        'index' => Dispatcher::DEFAULT_CONTROLLER,
        'default' => Dispatcher::DEFAULT_CONTROLLER,
        'itemset' => "ItemSetController",
    );


    /**
     * Fetch the correct controller, check if the action exist and call the action, it also verify the access right
     */
    public function init()
    {
        $controller = $this->getController();
        if (array_key_exists($controller, $this->controllers)) {
            $action = $this->getAction();
            $args =  Controller::bindArgs($controller, $action, explode("/", $this->getArgs()));
            $this->callAction($this->controllers[$controller], $this->getAction(),$args);
        } else {
            $this->callAction(Dispatcher::DEFAULT_CONTROLLER);
        }
    }

    /**
     * Call the action of the specified controller
     * An empty or null action will call the default action of the controller
     * @param String $controller the controller to call
     * @param String $action the action to call
     * @param array $args arguments to give to the action
     * @param bool $failSafe Display a fatal error when set at false and when an error occur
     */
    public function callAction($controller, $action = null, array $args = [], $failSafe = true)
    {
        $className = '\Nitixx\Controllers\\' . $controller;
        try {
            if (class_exists($className, true)) {
                $instance = new $className();
                if ($instance instanceof Controller) {  //Controller must extends Controller
                    if ($action != null && !empty($action)) { //Check if action exist
                        if ($instance->hasAction($action)) {
                            if ($instance->hasRightAccess($action)) {
                                $instance->$action($args);
                            } else {
                                $instance->accessDenied();
                            }
                        } else {
                            if ($failSafe) {
                                $this->displayError(404, Dispatcher::DEFAULT_CONTROLLER, 'displayError', array('code' => '404'));
                            } else {
                                $this->displayFatalError("Method " . $action . " does not exist!");
                            }
                        }
                    } else {
                        if ($failSafe) {
                            $this->displayError(404, Dispatcher::DEFAULT_CONTROLLER, 'displayError', array('code' => '404'));
                        } else {
                            $this->displayFatalError("Tied to call an empty action!");
                        }
                    }
                } else {
                    $this->displayFatalError("Class " . $controller . " does not extends Controller!");
                }
            } else {
                if ($failSafe) {
                    $this->displayError(404, Dispatcher::DEFAULT_CONTROLLER, 'displayError', array('code' => '404'));
                } else {
                    $this->displayFatalError("Class " . $controller . " does not exist!");
                }
            }
        }catch (\LeagueWrap\Response\Http503 $e){
            $this->displayError(2503, self::DEFAULT_CONTROLLER, 'riotAPIDown');
            throw $e;
        }catch (\PDOException $e ) {
            $this->displayError(1000, self::DEFAULT_CONTROLLER, 'databaseDown');
            throw $e;
        }catch (\Exception $e) {
            $this->displayFatalError("An internal error as happened, contact the administrator at
            admin@guillaumepierson.fr and say what you have done to get this error. I Would appreciate a lot");
            throw $e;
        }
    }

    /**
     * Create HTTP error page
     * @param int $errorNumber number error of the http protocol
     * @param String $controller Controller to fallback
     * @param String $action action to call
     * @param array $args arguments to give to the action
     */
    public function displayError($errorNumber, $controller, $action = null, array $args = [])
    {
        switch ($errorNumber) {
            case 404 :
                Utils::create404error();
                $this->callAction(Dispatcher::DEFAULT_CONTROLLER, 'error404', [], false);
                break;
            default:
                $this->callAction($controller, $action, $args, false);
                break;
        }
    }

    /**
     * To be used only because of wrong programming
     * @param String $message message to display
     */
    public function displayFatalError($message)
    {
        echo "Une erreur fatale a survenu : $message";
    }


    /**
     * Return the controller called by the user (POST or GET)
     * @return String return the controller
     */
    private function getController()
    {
        return isset($_GET['c']) ? (empty($_GET['c']) ? 'index' : $_GET['c'] ): 'index';
    }

    /**
     * Return the action called by the user (POST or GET)
     * @return String|null return the action
     */
    private function getAction()
    {
        return isset($_GET['a']) ? (empty($_GET['a']) ? 'index' : $_GET['a'] ) : 'index' ;
    }

    private function getArgs()
    {
        return isset($_GET['args']) ? $_GET['args'] : '';
    }
}