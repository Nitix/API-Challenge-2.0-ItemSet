<?php


namespace Nitixx\Models;


class ItemSetType extends FullNameEnum
{
    const CUSTOM = "custom";
    const GLOBAL_ = "global";

    /**
     * Give the ItemSetType corresponding to the ItemSetType
     * @param $text
     *
     * @return ItemSetType
     */
    public static function getItemSetType($text)
    {
        switch($text) {
            case ItemSetType::CUSTOM :
                return new ItemSetType(ItemSetType::CUSTOM);
            case ItemSetType::GLOBAL_ :
                return new ItemSetType(ItemSetType::GLOBAL_);
            default:
                return new ItemSetType(ItemSetType::CUSTOM);
        }
    }

    /**
     * Give the full name of the itemset
     * @param ItemSetType $itemSetType
     *
     * @return string
     */
    public static function getFullName(ItemSetType $itemSetType){
        return (string)$itemSetType;
    }

    /**
     * @inheritDoc
     */
    static function getByName($text)
    {
        return self::getItemSetType($text);
    }


}