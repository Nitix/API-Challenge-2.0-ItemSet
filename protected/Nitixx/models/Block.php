<?php


namespace models;
use LeagueWrap\Dto\StaticData\SummonerSpell;

/**
 * Class Block
 * @package models
 *          Represent a block within an item set
 */
class Block
{

    /**
     * @var \SplString name of the block
     */
    private $type;

    /**
     * @var \SplBool indicate if the block must be displayed as the tutorial formatting
     *              Default : false
     */
    private $recMath = false;

    /**
     * @var \SplInt minimum level of the account for the block to be displayed
     *          Default : -1 ( any account level)
     */
    private $minSummonerLevel;

    /**
     * @var \SplInt maximum level of the account for the block to be displayed
     *          Default : -1 ( any account level)
     */
    private $maxSummonerLevel;

    /**
     * @var SummonerSpell Display the block if the player has equipped a specific summoner spell.
     *                    Does not override hideIfSummonerSpell
     *                    If the value is null, an empty string will be displayed when converting to json
     */
    private $showIfSummonerSpell;

    /**
     * @var SummonerSpell Hide the block if the player has equipped a specific summoner spell.
     *                    Override hideIfSummonerSpell
     *                    If the value is null, an empty string will be displayed when converting to json
     */
    private $hideIfSummonerSpell;

    /**
     * @var ItemBlock[] List of items in the block
     */
    private $items;

    /**
     * @return \SplString
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param \SplString $type
     */
    public function setType(\SplString $type)
    {
        $this->type = $type;
    }

    /**
     * @return \SplBool
     */
    public function getRecMath()
    {
        return $this->recMath;
    }

    /**
     * @param \SplBool $recMath
     */
    public function setRecMath(\SplBool $recMath)
    {
        $this->recMath = $recMath;
    }

    /**
     * @return \SplInt
     */
    public function getMinSummonerLevel()
    {
        return $this->minSummonerLevel;
    }

    /**
     * @param \SplInt $minSummonerLevel
     */
    public function setMinSummonerLevel(\SplInt $minSummonerLevel)
    {
        $this->minSummonerLevel = $minSummonerLevel;
    }

    /**
     * @return \SplInt
     */
    public function getMaxSummonerLevel()
    {
        return $this->maxSummonerLevel;
    }

    /**
     * @param \SplInt $maxSummonerLevel
     */
    public function setMaxSummonerLevel(\SplInt $maxSummonerLevel)
    {
        $this->maxSummonerLevel = $maxSummonerLevel;
    }

    /**
     * @return SummonerSpell
     */
    public function getShowIfSummonerSpell()
    {
        return $this->showIfSummonerSpell;
    }

    /**
     * @param SummonerSpell $showIfSummonerSpell
     */
    public function setShowIfSummonerSpell(SummonerSpell $showIfSummonerSpell)
    {
        $this->showIfSummonerSpell = $showIfSummonerSpell;
    }

    /**
     * @return SummonerSpell
     */
    public function getHideIfSummonerSpell()
    {
        return $this->hideIfSummonerSpell;
    }

    /**
     * @param SummonerSpell $hideIfSummonerSpell
     */
    public function setHideIfSummonerSpell(SummonerSpell $hideIfSummonerSpell)
    {
        $this->hideIfSummonerSpell = $hideIfSummonerSpell;
    }

    /**
     * @return ItemBlock[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param ItemBlock[] $items
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }


}