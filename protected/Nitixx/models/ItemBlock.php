<?php


namespace models;


use LeagueWrap\Dto\StaticData\Item;

class ItemBlock
{

    /**
     * @var Item Item concerned
     */
    private $item;

    /**
     * @var \SplInt Number of times the item should be purchased.
     *          Default: 0
     */
    private $count = 0;

    /**
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param Item $item
     *
     * @return ItemBlock
     */
    public function setItem(Item $item)
    {
        $this->item = $item;
    }

    /**
     * @return \SplInt
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param \SplInt $count
     *
     * @return ItemBlock
     */
    public function setCount(\SplInt $count)
    {
        $this->count = $count;
    }


}