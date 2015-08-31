<?php


namespace Nitixx\Controllers;

use Nitixx\Models\ItemSet;
use Nitixx\Models\ItemSetType;
use Nitixx\Models\Map;
use Nitixx\Models\Mode;

class ItemSetController extends Controller
{
    private static $actions = ['index', 'upload', 'view', 'list', 'download', 'create', 'post'];

    /**
     * @inheritDoc
     */
    function hasAction($action)
    {
        return in_array($action, self::$actions);
    }

    /**
     * @inheritDoc
     */
    function hasRightAccess($action)
    {
        return true;
    }

    function indexAction()
    {
        $this->listAction();
    }

    /**
     * Verify the item set uploaded and create an item set with our Models
     * This help to add security as all data will be filtered
     */
    public function uploadAction()
    {
        $twig = $this->twigInit();
        if (isset($_FILES['itemset'])) {
            if ($_FILES['itemset']['error'] == UPLOAD_ERR_OK               //checks for errors
                && is_uploaded_file($_FILES['itemset']['tmp_name'])
            ) { //checks that file is uploaded
                if ($_FILES['itemset']['size'] < 51200) {
                    $parser = new ItemSetParser();
                    $json = json_decode(file_get_contents($_FILES['itemset']['tmp_name']), true);
                    if ($json != null) {
                        $parser->parse($json);
                        $itemSet = $parser->getItemSet();
                        $itemSet->save();
                        if(!empty($itemSet->getBlocks())) {
                            $itemSet->save();
                            header('Location: ' . Controller::generateURL('itemset', 'view', ['id' => $itemSet->getId()]));
                        }else{
                            $template = $twig->loadTemplate('upload.html.twig');
                            echo $template->render(['error' => 'You have no blocks']);
                        }
                   } else {
                        $template = $twig->loadTemplate('upload.html.twig');
                        echo $template->render(['error' => 'Incorrect Json file']);
                    }
                } else {
                    $template = $twig->loadTemplate('upload.html.twig');
                    echo $template->render(['error' => 'This file is too large']);
                }
            } else {
                $template = $twig->loadTemplate('upload.html.twig');
                echo $template->render(['error' => 'Attack attempt detected']);
            }
        } else {
            $template = $twig->loadTemplate('upload.html.twig');
            echo $template->render([]);
        }
    }

    /**
     * Render an item set stored in the database
     * @args id ID of the itemSet
     */
    public function viewAction($args = [])
    {
        $twig = $this->twigInit();
        if(!empty($args)) {
            $itemSet = ItemSet::findById($args['id']);
            if($itemSet){
                $itemSet->getBlocks();
                $this->displayItemSet($itemSet);
            }else{
                $template = $twig->loadTemplate('404.html.twig');
                echo $template->render([]);
            }
        } else {
            $template = $twig->loadTemplate('404.html.twig');
            echo $template->render([]);
        }
    }

    public function listAction($args = [])
    {
        $twig = $this->twigInit();
        $list = ItemSet::findAll();
        $template = $twig->loadTemplate('itemSetList.html.twig');
        $champions = ApiManager::getAPI()->staticData()->getChampions();
        $champions->sortByName();
        echo $template->render(['list' => $list, 'type' => new ItemSetType(), 'mode' => new Mode(), 'map' => new Map(), 'champions' =>  $champions]);
    }

    public function downloadAction($args = [])
    {

        if(!empty($args)) {
            $itemSet = ItemSet::findById($args['id']);
            if($itemSet){
                header('Content-disposition: attachment; filename="'. $itemSet->getTitle() .'.json"');
                header('Content-type: application/json');
                echo $itemSet->toJson();
            }else{
                $twig = $this->twigInit();
                $template = $twig->loadTemplate('404.html.twig');
                echo $template->render([]);
            }
        } else {
            $twig = $this->twigInit();
            $template = $twig->loadTemplate('404.html.twig');
            echo $template->render([]);
        }
    }

    public function createAction($args = [])
    {
        $twig = $this->twigInit();
        $api = ApiManager::getAPI();
        $items = $api->staticData()->getItems('all');
        $items->sortByGoldAndName();
        $champions = $api->staticData()->getChampions();
        $champions->sortByName();
        $template = $twig->loadTemplate('itemSetCreate.html.twig');
        echo $template->render(['items' => $items, 'version' => $api->staticData()->version()[0],
            'type' => new ItemSetType(), 'mode' => new Mode(), 'map' => new Map(), 'summonerSpell' => $api->staticData()->getSummonerSpells('all'), 'champions' => $champions ]);
    }

    public function postAction($args = []){
        $poster = new ItemSetPoster();
        $poster->parse();
        $itemSet = $poster->getItemSet();
        if(!empty($itemSet->getBlocks())) {
            $itemSet->save();
            header('Location: ' . Controller::generateURL('itemset', 'view', ['id' => $itemSet->getId()]));
        }else{
            header('Location: ' . Controller::generateURL('itemset', 'create'));
        }
   }

    private function displayItemSet(ItemSet $itemSet)
    {
        $twig = $this->twigInit();
        $template = $twig->loadTemplate('itemSet.html.twig');
        $api = ApiManager::getAPI();
        $champions = $api->staticData()->getChampions();
        $champions->sortByName();
        echo $template->render(['itemSet' => $itemSet, 'version' => $api->staticData()->version()[0], 'champions' =>  $champions]);
    }
}