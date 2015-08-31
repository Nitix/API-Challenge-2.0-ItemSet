<?php


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
 * Class ItemSetParser
 * @package Nitixx\Controllers
 *          Convert a json file to use our models
 */
class ItemSetParser
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
     * @param array $json Convert the given php array from json to the internal item set
     */
    public function parse(Array $json){
        //Title
        if(isset($json['title'])) {
            $this->itemSet->setTitle(new \SplString($json['title']));
        }else{
            $this->itemSet->setTitle(new \SplString("Item Set"));
        }

        //Type
        if(isset($json['type'])) {
            $this->itemSet->setType(ItemSetType::getItemSetType($json['type']));
        }

        //Map
        if(isset($json['map'])) {
            $this->itemSet->setMap(Map::getMap($json['map']));
        }

        //Mode
        if(isset($json['mode'])) {
             $this->itemSet->setMode(Mode::getMode($json['mode']));
        }

        //Priority
        if(isset($json['priority'])) {
            $this->itemSet->setPriority(new \SplBool($json['priority'] == true));
        }

        //Sort Rank
        if(isset($json['sortrank'])) {
            $this->itemSet->setSortrank(new \SplInt(@(int) $json['sortrank']));
        }

        if(isset($json['blocks'])){
            foreach($json['blocks'] as $json_block){
                $b = $this->parseBlock($json_block);
                if($b !== null)
                    $this->itemSet->addBlock($b);
            }
        }
    }

    /**
     * Parse a block from an array
     * @param array $json
     *
     * @return Block|null null if empty
     */
    private function parseBlock(Array $json){
        $block = new Block();

        $block->setItemSet($this->itemSet);

        //Type
        if(isset($json['type'])) {
            $block->setType($json['type']);
        }else{
            $block->setType("Block");
        }

        //RecMath
        if(isset($json['recMath'])) {
            $block->setRecMath(new \SplBool(@(bool)$json['recMath']));
        }

        //MinSummonerLevel
        if(isset($json['minSummonerLevel'])) {
            $block->setMinSummonerLevel(new \SplInt(@(int) $json['minSummonerLevel']));
        }

        //MaxSummonerLevel
        if(isset($json['maxSummonerLevel'])) {
            $block->setMaxSummonerLevel(new \SplInt(@(int) $json['maxSummonerLevel']));
        }

        //showIfSummonerSpell
        if(isset($json['showIfSummonerSpell'])){
            $spell = $this->summonerSpellList->getSpellFromKey($json['showIfSummonerSpell']);
            if($spell != null)
                $block->setShowIfSummonerSpell($spell);
        }

        //hideIfSummonerSpell
        if(isset($json['hideIfSummonerSpell'])){
            $spell = $this->summonerSpellList->getSpellFromKey($json['hideIfSummonerSpell']);
            if($spell != null)
                $block->setHideIfSummonerSpell($spell);
        }

        foreach($json['items'] as $item){
            $i = $this->parseItem($item, $block);
            if($i != null)
                $block->addItem($i);
        }
        if(empty($block->getItems()))
            return null;
        return $block;
    }

    /**
     * Convert an item block from array json to our models
     * @param array $json
     * @param Block $block
     * @return ItemBlock|null null if empty
     */
    private function parseItem(array $json, Block $block){
        $itemBlock = new ItemBlock();

        $itemBlock->setBlock($block);

        //Item concerned
        if(isset($json['id'])){
            $item = $this->itemList->getItem(@(int)$json['id']);
            if($item != null)
                $itemBlock->setItem($item);
            else
                return null;
        }else{
            return null;
        }

        //Count
        if(isset($json['count'])) {
            $itemBlock->setCount(new \SplInt(@(int) $json['count']));
        }

        return $itemBlock;
    }

    /**
     * @return ItemSet ItemSet modified or to going to be modified
     */
    public function getItemSet()
    {
        return $this->itemSet;
    }

}