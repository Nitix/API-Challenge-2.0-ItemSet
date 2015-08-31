<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 31/08/2015
 * Time: 10:54
 */

namespace Nitixx\Controllers;

use LeagueWrap\Dto\StaticData\ItemList;
use LeagueWrap\Dto\StaticData\SummonerSpellList;
use Nitixx\Models\Block;
use Nitixx\Models\ItemBlock;
use Nitixx\Models\ItemSet;
use Nitixx\Models\ItemSetType;
use Nitixx\Models\Map;
use Nitixx\Models\Mode;

/**
 * Class ItemSetPoster
 * @package Nitixx\Controllers
 *          Convert a POST Request to use our models
 */
class ItemSetPoster
{
    /**
     * @var ItemSet Item Set on which we are going to modify
     */
    private $itemSet;

    /**
     * @var SummonerSpellList
     */
    private $summonerSpellList;

    /**
     * @var ItemList
     */
    private $itemList;

    /**
     * @param ItemSet|null $itemSet Prepare the parser to use a specified ItemSet
     */
    public function __construct(ItemSet $itemSet = null)
    {
        $api = ApiManager::getAPI();
        $this->summonerSpellList = $api->staticData()->getSummonerSpells('all');
        $this->itemList = $api->staticData()->getItems('all');
        $this->itemSet = ($itemSet == null ? new ItemSet() : $itemSet);
    }

    /**
     * Convert the POST variable
     */
    public function parse(){
        //Title
        if(isset($_POST['title'])) {
            $this->itemSet->setTitle($_POST['title']);
        }else{
            $this->itemSet->setTitle("Item Set");
        }

        //Type
        if(isset($_POST['champion'])) {
            $this->itemSet->setChampion(@(int)$_POST['champion']);
        }

        //Type
        if(isset($_POST['type'])) {
            $this->itemSet->setType(ItemSetType::getItemSetType($_POST['type']));
        }

        //Map
        if(isset($_POST['map'])) {
            $this->itemSet->setMap(Map::getMap($_POST['map']));
        }

        //Mode
        if(isset($_POST['mode'])) {
            $this->itemSet->setMode(Mode::getMode($_POST['mode']));
        }

        //Priority
        if(isset($_POST['priority'])) {
            $this->itemSet->setPriority(new \SplBool($_POST['priority'] == true));
        }

        //Sort Rank
        if(isset($_POST['sortrank'])) {
            $this->itemSet->setSortrank(new \SplInt(@(int) $_POST['sortrank']));
        }

        //Comments
        if(isset($_POST['comment'])) {
            $this->itemSet->setComment($_POST['comment']);
        }

        if(isset($_POST['blocks'])){
            ksort($_POST['blocks']);
            foreach($_POST['blocks'] as $block){
                $this->itemSet->addBlock($this->parseBlock($block));
            }
        }
    }

    /**
     * Parse a block from an array
     * @param array $data
     *
     * @return Block
     */
    private function parseBlock(Array $data){
        $block = new Block();

        $block->setItemSet($this->itemSet);

        //Type
        if(isset($data['type'])) {
            $block->setType($data['type']);
        }else{
            $block->setType("Block");
        }

        //RecMath
        if(isset($data['recMath'])) {
            $block->setRecMath(new \SplBool(@(bool)$data['recMath']));
        }

        //MinSummonerLevel
        if(isset($data['minSummonerLevel'])) {
            $block->setMinSummonerLevel(new \SplInt(@(int) $data['minSummonerLevel']));
        }

        //MaxSummonerLevel
        if(isset($data['maxSummonerLevel'])) {
            $block->setMaxSummonerLevel(new \SplInt(@(int) $data['maxSummonerLevel']));
        }

        //showIfSummonerSpell
        if(isset($data['showIfSummonerSpell'])){
            $spell = $this->summonerSpellList->getSpell($data['showIfSummonerSpell']);
            if($spell != null)
                $block->setShowIfSummonerSpell($spell);
        }

        //hideIfSummonerSpell
        if(isset($data['hideIfSummonerSpell'])){
            $spell = $this->summonerSpellList->getSpell($data['hideIfSummonerSpell']);
            if($spell != null)
                $block->setHideIfSummonerSpell($spell);
        }

        //Comments
        if(isset($data['comment'])) {
            $block->setComment($data['comment']);
        }

        if(isset($data['items'])) {
            ksort($data['items']);
            $block->setItems($this->parseItems($data['items'], $block));

        }
        return $block;
    }

    /**
     * Convert an item block from array to our models
     * @param array $data
     * @param Block $block
     * @return ItemBlock[]
     */
    private function parseItems(array $data, Block $block){
        $items = [];
        $last = -1;
        foreach($data as $id){
            if($id != $last){
                $itemBlock = new ItemBlock();
                $itemBlock->setBlock($block);
                $item = $this->itemList->getItem($id);
                if($item != null) {
                    $itemBlock->setItem($item);
                    $itemBlock->setCount(new \SplInt(1));
                    $items[] = $itemBlock;
                    $last = $id;
                }
            }else{
                $add = end($items);
                $add->setCount(new \SplInt((int)$add->getCount() + 1));
            }
        }
        return $items;
    }

    /**
     * @return ItemSet ItemSet modified or to going to be modified
     */
    public function getItemSet()
    {
        return $this->itemSet;
    }

}