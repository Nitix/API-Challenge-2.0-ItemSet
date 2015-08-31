<?php


namespace models;
use LeagueWrap\Dto\StaticData\Item;

/**
 * Class ItemSet
 * @package models
 */

class ItemSet
{
    /**
     * @var \SplString Name of the item set
     *             Displayed in the game
     */
    private $title;

    /**
     * @var ItemSetType Type of item set
     *                  custom will be displayed first
     */
    private $type;

    /**
     * @var Map The map were the item set will appear on
     *             Possible value : ANY, SR, HA, TT, CS
     */
    private $map;

    /**
     * @var Mod The mode were the item set will appear on
     *             Possible value : ANY, CLASSIC, ODIN, ARAM
     */
    private $mod;

    /**
     * @var \SplBool Indicate if the item set as the priority over others item sets
     *              Override sortrank but not type
     *              Default : false
     */
    private $priority = false;

    /**
     * @var \SplInt The order in which this item set will be sorted within a specific type.
     *          Item sets are sorted in descending order.
     */
    private $sortrank;

    /**
     * @var Block[] List of blocks of the item set
     */
    private $blocks = [];

    /**
     * @return \SplString
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param \SplString $title
     */
    public function setTitle(\SplString $title)
    {
        $this->title = $title;
    }

    /**
     * @return ItemSetType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param ItemSetType $type
     */
    public function setType(ItemSetType $type)
    {
        $this->type = $type;
    }

    /**
     * @return Map
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * @param Map $map
     */
    public function setMap(Map $map)
    {
        $this->map = $map;
    }

    /**
     * @return Mod
     */
    public function getMod()
    {
        return $this->mod;
    }

    /**
     * @param Mod $mod
     */
    public function setMod(Mod $mod)
    {
        $this->mod = $mod;
    }

    /**
     * @return \SplBool
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param \SplBool $priority
     */
    public function setPriority(\SplBool $priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return \SplInt
     */
    public function getSortrank()
    {
        return $this->sortrank;
    }

    /**
     * @param \SplInt $sortrank
     */
    public function setSortrank(\SplInt $sortrank)
    {
        $this->sortrank = $sortrank;
    }

    /**
     * @return Block[]
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @param Block[] $blocks
     */
    public function setBlocks(array $blocks)
    {
        $this->blocks = $blocks;
    }

}